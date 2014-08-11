angular.module('mavenBooksApp').controller('TaxonomyController', ['$scope', '$http', function($scope, $http) {
		$scope.smartTermRules = [];
		$scope.smartOperator = {};
		$scope.isSmartTerm = CachedIsSmartTerm;
		$scope.smartTermOperator = CachedSmartTermOperators;
		$scope.smartTermFields = CachedSmartTermFields;
		$scope.smartOperator.operators = [{value: "AND"}, {value: "OR"}];
		angular.forEach(CachedIsSmartTerm, function(rule) {
			$scope.smartTermRules.push(rule);
		});
		console.log($scope.smartTermRules);
		console.log($scope.smartTermOperator);

		$scope.addSmartTermRule = function(evt) {
			evt.preventDefault();
			console.log($scope.smartOperator);
			if ($scope.smartOperator.hasOwnProperty('selected')) {
				var newRule = {};
				$scope.smartTermRules.push(newRule);
			}
		};
		$scope.removeTermRule = function(idx) {
			$scope.smartTermRules.splice(idx, 1);
		};

	}]);
 