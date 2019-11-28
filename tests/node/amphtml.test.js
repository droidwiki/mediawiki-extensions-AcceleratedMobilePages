var ampHtmlValidator = require('amphtml-validator');
var fs = require('fs');
var path = require('path');

function logAmpFailures(result) {
	for (var ii = 0; ii < result.errors.length; ii++) {
		var error = result.errors[ii];
		var msg = 'line ' + error.line + ', col ' + error.col + ': ' + error.message;
		if (error.specUrl !== null) {
			msg += ' (see ' + error.specUrl + ')';
		}
		(error.severity === 'ERROR' ? console.error : console.warn)(msg);
	}
}

describe('ampHtml', function () {
	var ampHtml;

	beforeAll(function () {
		ampHtml = fs.readFileSync(path.join(__dirname, '../amp.html'), "utf8");
	});

	it('is valid AMP HTML', function (done) {
		ampHtmlValidator.getInstance().then(function (validator) {
			var result = validator.validateString(ampHtml);
			try {
				expect(result.status).toBe('PASS');
			} catch (e) {
				logAmpFailures(result);
				done(e);
				return;
			}
			done();
		});
	});

	it('matches snapshot', function () {
		expect(ampHtml).toMatchSnapshot();
	});
});
