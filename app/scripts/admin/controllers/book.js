angular.module('mavenBooksApp').controller('BooksCtrl', ['$scope', '$http', function($scope, $http) {
		$scope.book = CachedBook;
		$scope.statuses = [{name: 'Available', value: 'available'},
			{name: "Not Available", value: 'not_available'}];
		if ($scope.book.hasOwnProperty('status')) {
			console.log($scope.book.status);
			if ($scope.book.status === null) {
				$scope.book.status = 'available';
			}
		}

	}]);
 