const getArguments = () => {
    return JSON.parse(process.env.arguments);
};

getArguments.parse = () => {
    const arguments = {};
    process.argv.forEach(argument => {
        if (argument.indexOf('--') !== 0) {
            return;
        }

        const argParts = argument.slice(2).split('=');
        arguments[argParts[0]] = (argParts.length > 1) ? argParts[1] : true;
    });

    process.env.arguments = JSON.stringify(arguments);
};

module.exports = getArguments;
