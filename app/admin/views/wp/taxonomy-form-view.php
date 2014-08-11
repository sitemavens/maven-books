<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedSmartTermRules', $smartTermRules ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedIsSmartTerm', $isSmartTerm ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedSmartTermFields', $smartTermFields ); ?>
<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedSmartTermOperators', $smartTermOperators ); ?>
<div >	
	<table class="form-table" ng-controller="TaxonomyController">
		<tr class="is-smart-rules-form">
			<td>
				<?php $lang->_e( 'Is Smart Category' ); ?>
			</td>
			<td>
				<input type="checkbox" name="mvn_smart_rules[is_smart_term]" ng-model="isSmartTerm" ng-true-value="'YES'" ng-false-value="'NO'"/>
			</td>
		</tr>
		<tr class="smart-rules-form" ng-if="isSmartTerm">
			<td colspan="2">
				<div class="mvn-shop-category-form-container clear clearfix">
					<button class="button-secondary" 
							ng-click="addSmartTermRule($event)">
						Add Rule
					</button>
					<select name="mvn_smart_rules[mvn_shop_term][smart_operator]" ng-model="smartOperator.selected" ng-options="i.value for i in smartOperator.operators" >
					</select>
					<ul class="smart-rules">
						<li class="smart-rule" ng-repeat="termRule in smartTermRules">
							<button class="add-rule" ng-click="removeTermRule($index)">
								X
							</button>
							<select class="add-rule" name="mvn_smart_rules[mvn_shop_term][smart_rules][{{$index}}][field]" ng-model="termRule.termField" ng-options="i as value for (i,value) in smartTermFields">
							</select>
							<select class="add-rule" name="mvn_smart_rules[mvn_shop_term][smart_rules][{{$index}}][operator]" ng-model="termRule.termOperator" ng-options="i as value for (i,value) in smartTermOperator">
							</select>
							<input type="text" ng-if="termRule.termField && termRule.termOperator" name="mvn_smart_rules[mvn_shop_term][smart_rules][{{$index}}][rule]" class="col-sm-5" ng-model="termRule.rule"/>
						</li>
						<?php // if ( $smart_term_rules ):    ?>
						<?php // foreach ( $smart_term_rules as $key => $smart_rule ):    ?>
							<!--<li id="mvn-shop-smart-rule-////<?php // echo $key;            ?>" class="mvn-shop-smart-rule">-->
								<!--<label><button id="remove-smart-rule-////<?php // echo $key;            ?>" class="button-secondary remove_smart_rule">X</button> <?php // $lang->_e( 'Rule' );           ?></label>-->
						<?php // Mvn_Shop_Product_Taxonomies::dropdown_fields( array( 'selected' => $smart_rule[ 'field' ], 'name' => "mvn_shop_term[smart_rules][{$key}][field]", 'id' => "mvn-shop-category-smart-field-{$key}" ) );    ?>
						<?php // Mvn_Shop_Product_Taxonomies::dropdown_operators( array( 'selected' => $smart_rule[ 'operator' ], 'name' => "mvn_shop_term[smart_rules][{$key}][operator]", 'id' => "mvn-shop-category-smart-operator-{$key}" ) );    ?>
						<!--<input type="text" value="////<?php // echo esc_attr( $smart_rule[ 'rule' ] );            ?>" name="mvn_shop_term[smart_rules][<?php // echo $key;           ?>][rule]" />-->
						<!--</li>-->
						<?php // endforeach;    ?>
						<?php // endif;    ?>
					</ul>
				</div>
			</td>
		</tr>
	</table>
</div>
