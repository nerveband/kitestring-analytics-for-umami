const { spawnSync } = require('node:child_process');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const hasPlugin = spawnSync('docker', ['compose', 'version'], { stdio: 'ignore' }).status === 0;
const executable = hasPlugin ? 'docker' : 'docker-compose';
const prefix = hasPlugin ? ['compose'] : [];

function compose(args, options = {}) {
    return spawnSync(executable, [...prefix, ...args], { cwd: root, ...options });
}

function checked(args) {
    const result = compose(args, { stdio: 'inherit' });
    if (result.error) throw result.error;
    if (result.status !== 0) process.exit(result.status || 1);
}

if (require.main === module) {
    const [command, container, ...args] = process.argv.slice(2);
    if (command === 'start') {
        checked(['up', '-d', '--wait']);
        if (process.env.WORDPRESS_VERSION) {
            checked(['exec', '-T', 'cli', 'wp', 'core', 'download', '--force', '--skip-content', `--version=${process.env.WORDPRESS_VERSION}`]);
        }
        if (compose(['exec', '-T', 'cli', 'wp', 'core', 'is-installed'], { stdio: 'ignore' }).status !== 0) {
            checked(['exec', '-T', 'cli', 'wp', 'core', 'install', `--url=http://localhost:${process.env.WP_PORT || 8888}`,
                '--title=Simply Analytics Test', '--admin_user=admin', '--admin_password=password', '--admin_email=admin@example.test', '--skip-email']);
        }
        checked(['exec', '-T', 'cli', 'wp', 'plugin', 'activate', 'simply-analytics-for-umami']);
    } else if (command === 'stop') {
        checked(['stop']);
    } else if (command === 'run') {
        checked(['exec', '-T', container, ...(args[0] === '--' ? args.slice(1) : args)]);
    } else {
        console.error('Usage: npm run env -- start|stop|run <service> -- <command>');
        process.exit(1);
    }
}

module.exports = { compose };
