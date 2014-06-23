<?php \Maven\Core\UI\HtmlComponent::jSonComponent( 'CachedBook', $book ); ?>

<div ng-controller="BooksCtrl">	

	<input type="hidden" name="mvn[book][id]" ng-value="event.id" />

	<tabset>
		<tab heading="General">
			<div class="form-horizontal" style="margin:15px 0;">
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Price:</label>
					<div class="col-sm-5">
						<input class="form-control" type="input" ng-model="book.price" name="mvn[book][price]"  />
					</div>
				</div>
				 
			</div>
		</tab>
		 
	</tabset>


	<div class="alert alert-danger" ng-show="post.$invalid">
		<span ng-show="post.$error.required">Required elements</span>
		<span ng-show="post.$error.invalid">Invalid elements</span>
	</div>


</div>
