<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedSmartTermRules', $smartTermRules ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedIsSmartTerm', $isSmartTerm ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedSmartTermFields', $smartTermFields ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedSmartTermOperators', $smartTermOperators ); ?>
<div >	
	<table class="form-table smart-rules-form" ng-controller="TaxonomyController">
		<tr>
		<td colspan="2">
			<alert type="alert" ng-hide="smartIsUnique">There should be no duplicate rules</alert>
			<alert type="alert" ng-show="smartTermRules.length === 0 && isSmartTerm">There should be at least one rule</alert>
		</td>
		</tr>
		<tr class="is-smart-row">
			<td>
				<?php $lang->_e( 'Is Smart Category' ); ?>
			</td>
			<td>
				<input type="checkbox" name="mvn_smart_rules[is_smart_term]" ng-model="isSmartTerm" ng-change="checkForDuplicates()"
					   ng-true-value="1" ng-false-value="0"/>
			</td>
		</tr>
		<tr  ng-if="isSmartTerm">
			<td colspan="2">
				<div class="mvn-shop-category-form-container clear clearfix">
					<button class="button-secondary" 
							ng-click="addSmartTermRule($event)">
						Add Smart Rules
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
						<?php // if ( $smart_term_rules ):    ?>
						<?php // foreach ( $smart_term_rules as $key => $smart_rule ):    ?>
							<!--<li id="mvn-shop-smart-rule-////<?php // echo $key;                        ?>" class="mvn-shop-smart-rule">-->
								<!--<label><button id="remove-smart-rule-////<?php // echo $key;                        ?>" class="button-secondary remove_smart_rule">X</button> <?php // $lang->_e( 'Rule' );                       ?></label>-->
						<?php // Mvn_Shop_Product_Taxonomies::dropdown_fields( array( 'selected' => $smart_rule[ 'field' ], 'name' => "mvn_shop_term[smart_rules][{$key}][field]", 'id' => "mvn-shop-category-smart-field-{$key}" ) );    ?>
						<?php // Mvn_Shop_Product_Taxonomies::dropdown_operators( array( 'selected' => $smart_rule[ 'operator' ], 'name' => "mvn_shop_term[smart_rules][{$key}][operator]", 'id' => "mvn-shop-category-smart-operator-{$key}" ) );    ?>
						<!--<input type="text" value="////<?php // echo esc_attr( $smart_rule[ 'rule' ] );                        ?>" name="mvn_shop_term[smart_rules][<?php // echo $key;                       ?>][rule]" />-->
						<!--</li>-->
						<?php // endforeach;    ?>
						<?php // endif;    ?>
					</ul>
				</div>
			</td>
		</tr>
	</table>
</div>
