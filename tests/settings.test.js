const { test, expect } = require('@playwright/test');
const { runCommand } = require('./helper/wpcli-command');
const configured = {
    enabled: 1,
    script_url: 'https://umami.example.com/script.js',
    website_id: 'e676c9b4-11e4-4ef1-a4d7-87001773e9f2',
    host_url: 'https://collect.example.com/base?x=1&y=2',
    use_host_url: 1,
    ignore_admins: 1,
    auto_track: 0,
    do_not_track: 1,
    track_comments: 0,
};
function setOptions(options) {
    runCommand(`option update kitestring_umami_options ${JSON.stringify(options)} --format=json --quiet`);
}
async function login(page) {
    await page.goto('/wp-login.php');
    await page.fill('#user_login', process.env.TEST_USER || 'admin');
    await page.fill('#user_pass', process.env.TEST_PASS || 'password');
    await page.click('#wp-submit');
    await page.waitForURL(/wp-admin/);
}
test.beforeEach(() => setOptions(configured));
test('collection URL and tracking controls survive HTML parsing', async ({ page }) => {
    await page.goto('/');
    const script = page.locator('script[data-website-id]');
    await expect(script).toHaveAttribute('data-website-id', configured.website_id);
    await expect(script).toHaveAttribute('data-auto-track', 'false');
    await expect(script).toHaveAttribute('data-do-not-track', 'true');
    await expect(script).toHaveAttribute('data-host-url', configured.host_url);
});
test('disabled tracking emits no tracker', async ({ page }) => {
    setOptions({ ...configured, enabled: 0 });
    await page.goto('/');
    await expect(page.locator('script[data-website-id]')).toHaveCount(0);
});
test('administrator exclusion does not exclude anonymous visitors', async ({ page }) => {
    await login(page);
    await page.goto('/');
    await expect(page.locator('script[data-website-id]')).toHaveCount(0);
    await page.context().clearCookies();
    await page.goto('/');
    await expect(page.locator('script[data-website-id]')).toHaveAttribute('data-website-id', configured.website_id);
});
test('deactivation and reactivation retain configured tracking', async ({ page }) => {
    runCommand('plugin deactivate kitestring-analytics-for-umami --quiet');
    await page.goto('/');
    await expect(page.locator('script[data-website-id]')).toHaveCount(0);
    runCommand('plugin activate kitestring-analytics-for-umami --quiet');
    await page.goto('/');
    await expect(page.locator('script[data-website-id]')).toHaveAttribute('data-website-id', configured.website_id);
});
test('legacy migration does not overwrite subsequently saved settings', async ({ page }) => {
    runCommand(`option update integrate_umami_options ${JSON.stringify(configured)} --format=json --quiet`);
    runCommand('option delete kitestring_umami_options --quiet');
    await page.goto('/');
    await expect(page.locator('script[data-website-id]')).toHaveAttribute('data-website-id', configured.website_id);
    setOptions({ ...configured, website_id: 'b59e9c65-ae32-47f1-8400-119fcf4861c4' });
    await page.goto('/');
    await expect(page.locator('script[data-website-id]')).toHaveAttribute('data-website-id', 'b59e9c65-ae32-47f1-8400-119fcf4861c4');
});

test('previous branded settings and credentials migrate without overwriting current settings', async ({ page }) => {
    const legacy = { ...configured, api_username: 'legacy-user', api_password: '  migration <&> password  ' };
    runCommand('option update simply_umami_options', [JSON.stringify(legacy), '--format=json', '--quiet']);
    runCommand('option delete kitestring_umami_options --quiet');
    await page.goto('/');
    await expect(page.locator('script[data-website-id]')).toHaveAttribute('data-website-id', configured.website_id);
    const migrated = JSON.parse(runCommand('option get kitestring_umami_options --format=json'));
    expect(migrated).toEqual(legacy);
    setOptions({ ...configured, enabled: 0 });
    await page.goto('/');
    await expect(page.locator('script[data-website-id]')).toHaveCount(0);
    runCommand('option delete simply_umami_options --quiet');
});

test('recording opt-in preserves ports and paths without inheriting tracker query or fragment', async ({ page }) => {
    await page.goto('/');
    await expect(page.locator('#kitestring-umami-recorder-js')).toHaveCount(0);
    setOptions({
        ...configured,
        script_url: 'https://umami.example.com:8443/assets/script.js?proxy=https://other.example.com/path#fragment',
        recorder_enabled: 1,
    });
    await page.goto('/');
    await expect(page.locator('#kitestring-umami-recorder-js')).toHaveAttribute('src', 'https://umami.example.com:8443/assets/recorder.js');
});

test('tracking disabled or administrator exclusion also prevents recorder loading', async ({ page }) => {
    setOptions({ ...configured, recorder_enabled: 1, enabled: 0 });
    await page.goto('/');
    await expect(page.locator('#kitestring-umami-recorder-js')).toHaveCount(0);
    setOptions({ ...configured, recorder_enabled: 1 });
    await login(page);
    await page.goto('/');
    await expect(page.locator('#kitestring-umami-recorder-js')).toHaveCount(0);
});

test('dashboard link preserves a custom port and application base path', async ({ page }) => {
    setOptions({
        ...configured,
        script_url: 'https://umami.example.com:8443/analytics/script.js?version=3#fragment',
        host_url: '',
        use_host_url: 0,
    });
    await login(page);
    await expect(page.locator('#umami_widget a')).toHaveAttribute(
        'href', `https://umami.example.com:8443/analytics/websites/${configured.website_id}`
    );
});

test('saving API passwords preserves spaces and special characters', async ({ page }) => {
    test.setTimeout(60000);
    await login(page);
    await page.goto('/wp-admin/options-general.php?page=kitestring-analytics-for-umami');
    await page.locator('#api-options summary').click();
    const password = '  example <tag> & %20 password  ';
    await page.locator('#kitestring_umami_api_password').fill(password);
    await page.getByRole('button', { name: 'Save settings' }).click();
    await page.waitForURL(/settings-updated=true/);
    await expect(page.locator('#kitestring_umami_api_password')).toHaveValue(password);
});

test('subscribers cannot view the analytics dashboard widget', async ({ page }) => {
    runCommand('user create umami-subscriber subscriber@example.test --role=subscriber --user_pass=test-subscriber-password --quiet');
    try {
        await page.goto('/wp-login.php');
        await page.fill('#user_login', 'umami-subscriber');
        await page.fill('#user_pass', 'test-subscriber-password');
        await page.click('#wp-submit');
        await page.waitForURL(/wp-admin/);
        await page.goto('/wp-admin/');
        await expect(page.locator('#umami_widget')).toHaveCount(0);
    } finally {
        runCommand('user delete umami-subscriber --yes --quiet');
    }
});

test('self-hosted API login sends the saved password unchanged', () => {
    const code = `
        $password = '  example <tag> & %20 password  ';
        $options = get_option('kitestring_umami_options');
        $options['api_username'] = 'test-user';
        $options['api_password'] = $password;
        update_option('kitestring_umami_options', $options);
        add_filter('pre_http_request', function($response, $args, $url) use ($password) {
            $body = json_decode($args['body'], true);
            if ($body['password'] !== $password) {
                throw new Exception('API password was modified');
            }
            return array('response' => array('code' => 200), 'body' => '{"token":"test-token"}');
        }, 10, 3);
        if (!KitestringUmami\\ApiClient::from_options()->login()) {
            throw new Exception('API login failed');
        }
        echo 'password-preserved';
    `;
    const encoded = Buffer.from(code).toString('base64');
    expect(runCommand(`eval eval(base64_decode('${encoded}'));`)).toContain('password-preserved');
});
