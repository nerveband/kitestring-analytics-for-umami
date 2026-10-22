const { compose } = require('../../scripts/env');

function runCommand(command, whiteSpaceArg = false) {
    const args = ['exec', '-T', 'cli', 'wp', ...command.split(' ')];
    if (whiteSpaceArg) args.push(...[].concat(whiteSpaceArg));
    const result = compose(args);
    if (result.error) throw result.error;
    if (result.status !== 0) {
        throw new Error(`WP-CLI command failed: ${result.stderr.toString()}`);
    }
    return result.stdout.toString().trim() || result.stderr.toString().trim();
}

module.exports = { runCommand };
