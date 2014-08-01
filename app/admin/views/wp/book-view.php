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
					<label for="" class="col-sm-2 control-label">Sale Price:</label>
					<div class="col-sm-5">
						<input class="form-control" type="input" ng-model="book.salePrice" name="mvn[book][salePrice]"  />
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
					<label for="" class="col-sm-2 control-label">Inventory ID:</label>
					<div class="col-sm-5">
						<input class="form-control" type="input" ng-model="book.inventoryId" name="mvn[book][inventoryId]"  />
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">ISBN:</label>
					<div class="col-sm-5">
						<input class="form-control" type="input" ng-model="book.isbn" name="mvn[book][isbn]"  />
					</div>
				</div>

			</div>
		</tab>
		<tab heading="Details">
			<div class="form-horizontal" style="margin:15px 0;">
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Featured:</label>
					<div class="col-sm-5">
						<input class="form-control" type="checkbox" value="1" ng-model="book.featured" name="mvn[book][featured]"/>
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Special Book:</label>
					<div class="col-sm-5">
						<input class="form-control" type="checkbox" value="1" ng-model="book.special" name="mvn[book][special]"/>
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Date Imprinted:</label>
					<div class="col-sm-5">
						<input class="form-control" type="checkbox" value="1" ng-model="book.dateImprinted" name="mvn[book][dateImprinted]"/>
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Footnote:</label>
					<div class="col-sm-5">
						<textarea class="form-control" ng-model="book.footnote" name="mvn[book][footnote]"></textarea>
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Imprint:</label>
					<div class="col-sm-5">
						<textarea class="form-control" ng-model="book.imprint" name="mvn[book][imprint]"></textarea>
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Publication Date:</label>
					<div class="col-sm-5">
						<input class="form-control" type="input" ng-model="book.publicationDate" name="mvn[book][publicationDate]"  />
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Publication Place:</label>
					<div class="col-sm-5">
						<input class="form-control" type="input" ng-model="book.publicationPlace" name="mvn[book][publicationPlace]"  />
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Publication Year:</label>
					<div class="col-sm-5">
						<input class="form-control" type="input" ng-model="book.publicationYear" name="mvn[book][publicationYear]"  />
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Bibliography:</label>
					<div class="col-sm-5">
						<input class="form-control" type="input" ng-model="book.bibliography" name="mvn[book][bibliography]"  />
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Author:</label>
					<div class="col-sm-5">
						<input class="form-control" type="input" ng-model="book.author" name="mvn[book][author]"  />
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Subtitle:</label>
					<div class="col-sm-5">
						<textarea class="form-control" ng-model="book.subtitle" name="mvn[book][subtitle]"></textarea>
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Manage Stock:</label>
					<div class="col-sm-5">
						<input class="form-control" type="checkbox" ng-model="book.stockEnabled" value="1" name="mvn[book][stockEnabled]"/>
					</div>
				</div>
				<div class="form-group"  >
					<label for="" class="col-sm-2 control-label">Stock:</label>
					<div class="col-sm-5">
						<input class="form-control" type="input" ng-model="book.stockQuantity" name="mvn[book][stockQuantity]"  />
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
