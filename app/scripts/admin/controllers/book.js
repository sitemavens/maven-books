angular.module('mavenBooksApp').controller('BooksCtrl', ['$scope', '$http', function($scope, $http) {
		$scope.book = CachedBook;
		$scope.statuses = [{name: 'Available', value: 'available'},
			{name: "Not Available", value: 'not_available'}];
	}]);
 