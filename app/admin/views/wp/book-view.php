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
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Is Reserved:</label>
					<div class="col-sm-5">
						<input class="form-control" type="checkbox" ng-model="book.reserved" value="1" name="mvn[book][reserved]"/>
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Reservation Password:</label>
					<div class="col-sm-5">
						<input class="form-control" type="input" ng-model="book.reservationPassword" name="mvn[book][reservationPassword]"  />
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Featured:</label>
					<div class="col-sm-5">
						<input class="form-control" type="checkbox" value="1" ng-model="book.featured"/>
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Isbn:</label>
					<div class="col-sm-5">
						<input class="form-control" type="input" ng-model="book.isbn" name="mvn[book][isbn]"  />
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Publication Place:</label>
					<div class="col-sm-5">
						<input class="form-control" type="input" ng-model="book.publicationPlace" name="mvn[book][publicationPlace]"  />
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
