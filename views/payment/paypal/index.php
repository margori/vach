
<?php
?>
<div id="paypal-button-container" class="col-md-offset-3 col-md-6"></div>
<p id="result-message"></p>
<script>
   var referenceCode = "<?=$buyModel->referenceCode?>";
</script>

<!-- Initialize the JS-SDK -->
<script
  src="https://www.paypal.com/sdk/js?client-id=<?=Yii::$app->params['paypal']['CLIENT_ID']?>&components=buttons"
  data-sdk-integration-source="developer-studio"
></script>
<script src="js/paypal.js"></script>