<tr class="form-field">
	<td colspan="2">
	<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedSmartTermRules', $smartTermRules ); ?>
	<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedIsSmartTerm', $isSmartTerm ); ?>
	<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedSmartTermFields', $smartTermFields ); ?>
	<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedSmartTermOperators', $smartTermOperators ); ?>
	<table class="smart-rules-form" ng-controller="TaxonomyController">
		
		<tr class="is-smart-row">
			<th>
				<?php $lang->_e( 'Is Smart Category' ); ?>
			</th>
			<td style="text-align: left;">
				<input style="width: 20px;" type="checkbox" name="mvn_smart_rules[is_smart_term]" ng-model="isSmartTerm" ng-change="checkForDuplicates()"
					   ng-true-value="1" ng-false-value="0"/>
			</td>
		</tr>
		<tr ng-if="isSmartTerm">
			<th>
				<?php $lang->_e( 'Smart Rules' ); ?>
			</th>
			<td>
				<div class="mvn-shop-category-form-container clear clearfix">
					<button class="button-secondary" 
							ng-click="addSmartTermRule($event)">
						<?php $lang->_e( 'Add Smart Rules' ); ?>
					</button>
					<select ng-model="smartOperator.selected" ng-options="i.value as i.name for i in smartOperator.operators" >
					</select>
					<input type="hidden" name="mvn_smart_rules[mvn_shop_term][smart_operator]" value="{{smartOperator.selected}}" >
					<ul class="smart-rules">
						<li class="smart-rule" ng-repeat="termRule in smartTermRules">
							<button class="add-rule" ng-click="removeTermRule($index)">
								X
							</button>
							<select class="add-rule" name="mvn_smart_rules[mvn_shop_term][smart_rules][{{$index}}][field]" ng-model="termRule.field" ng-options="i as value for (i,value) in smartTermFields" ng-change="checkForDuplicates()">
							</select>
							<select class="add-rule" name="mvn_smart_rules[mvn_shop_term][smart_rules][{{$index}}][operator]" ng-model="termRule.operator" ng-options="i as value for (i,value) in smartTermOperator" ng-change="checkForDuplicates()">
							</select>
							<input type="text" ng-if="termRule.field && termRule.operator" name="mvn_smart_rules[mvn_shop_term][smart_rules][{{$index}}][rule]" class="col-sm-5" ng-model="termRule.rule" ng-blur="checkForDuplicates()"/>
						</li>
					</ul>
				</div>
				<div>
					<alert type="alert" ng-hide="smartIsUnique"><?php $lang->_e( 'There should be no duplicate rules' ); ?></alert>
					<alert type="alert" ng-show="smartTermRules.length === 0 && isSmartTerm"><?php $lang->_e( 'There should be at least one rule' ); ?></alert>
				</div>
			</td>
		</tr>
	</table>
	</td>
</tr>
