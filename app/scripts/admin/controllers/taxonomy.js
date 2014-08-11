angular.module('mavenBooksApp').controller('TaxonomyController', ['$scope', '$http', function($scope, $http) {
		$scope.smartTermRules = [];
		$scope.smartOperator = {};
		$scope.isSmartTerm = CachedIsSmartTerm;
		$scope.smartTermOperator = CachedSmartTermOperators;
		$scope.smartTermFields = CachedSmartTermFields;
		$scope.smartOperator.operators = [{value: "AND", name: "AND"}, {value: "OR", name: "OR"}];
		if (CachedSmartTermRules.smart_operator === "") {
			$scope.smartOperator.selected = "AND";
		} else {
			$scope.smartOperator.selected = CachedSmartTermRules.smart_operator;
		}
		angular.forEach(CachedSmartTermRules.smart_rules, function(rule) {
			$scope.smartTermRules.push(rule);
		});

		$scope.addSmartTermRule = function(evt) {
			evt.preventDefault();
			if ($scope.smartOperator.hasOwnProperty('selected')) {
				var newRule = {};
				newRule.field = "meta:mvn_shop_regular_price";
				newRule.operator = "is_greater_or_equal_than";
				$scope.smartTermRules.push(newRule);
			}
		};
		$scope.removeTermRule = function(idx) {
			$scope.smartTermRules.splice(idx, 1);
		};

	}]);
 