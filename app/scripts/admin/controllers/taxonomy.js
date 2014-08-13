angular.module('mavenBooksApp').controller('TaxonomyController', ['$scope', '$http', function($scope, $http) {
		$scope.smartTermRules = [];
		$scope.smartOperator = {};
		$scope.smartIsUnique = true;
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
				newRule.field = "book:price";
				newRule.operator = "is_greater_or_equal_than";
				$scope.smartTermRules.push(newRule);
				$scope.checkForDuplicates();
			}
		};
		$scope.removeTermRule = function(idx) {
			$scope.smartTermRules.splice(idx, 1);
			$scope.checkForDuplicates();
		};

		$scope.checkForDuplicates = function() {
			if ($scope.isSmartTerm) {
				if ($scope.smartTermRules.length === 0) {
					angular.element(document.getElementById('submit')).prop("disabled", "disabled");
					return false;
				}
				for (var i = 0; i < $scope.smartTermRules.length; i++)
				{
					for (var j = 0; j < $scope.smartTermRules.length; j++)
					{
						if (i !== j)
						{
							if ($scope.smartTermRules[i].field === $scope.smartTermRules[j].field &&
									$scope.smartTermRules[i].operator === $scope.smartTermRules[j].operator &&
									$scope.smartTermRules[i].rule === $scope.smartTermRules[j].rule)
							{
								$scope.smartIsUnique = false;
								angular.element(document.getElementById('submit')).prop("disabled", "disabled");
								return false;
							}
						}
					}
				}

			}
			$scope.smartIsUnique = true;
			angular.element(document.getElementById('submit')).removeProp("disabled");
			return true;
		};
	}]);
 