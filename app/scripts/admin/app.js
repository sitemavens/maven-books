
angular.module('mavenBooksApp', [
	'ngCookies',
	'ngResource',
	'ngSanitize',
	'ngRoute',
	'ui.bootstrap'
]).config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

		$httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';

	}]);
 