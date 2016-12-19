<html ng-app="textInputExample"> 
<head>
<base href="/QRCodeInHTML/">
<link rel="stylesheet" href="bootstrap.min.css">
<style>p, span	{ font-size: x-large; }</style>
</head>
<body ng-controller="QRController" style="margin: 1em;">
<?php
$credential = shell_exec('vipaccess provision -t VSMT -p');
$credentials = explode("\n", $credential);
$otpauth = $credentials[1];
$serial = preg_match('/:(\w+)\?/', $otpauth, $matches);
$serial = $matches[1];
$secret = preg_match('/=(\w+)\&/', $otpauth, $matches);
$secret = $matches[1];
$oath6 = shell_exec("oathtool -d6 -b -w1 --totp $secret");
$oath6 = explode("\n", $oath6);
?>
<h1>Use Google Authenticator/TOTP/2FA on PayPal and eBay</h1>
<h2><a href="https://signin.ebay.com/ws/eBayISAPI.dll?ActivateSecurityToken">Enable on eBay</a></h2>
<h2><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_security-token">Enable on PayPal</a></h2>
<p>Serial: <?= $serial ?></p>
<p>1st code: <?= $oath6[0] ?></p>
<p>2st code: <?= $oath6[1] ?></p>
<h2>Barcode for Google Authenticator</h2>
<div class="row">
	<div class="col-md-12">
		<input type="hidden" name="input" ng-model="url" ng-change="change()" >
	</div>
</div>

<div class="row">
	<div class="col-md-5">
		<canvas id="neocotic-qr-code"></canvas>
	</div>
</div>
<h2>String for 1Password</h2>
<p>Secret: <?= $secret ?></p>
	<div class="col-md-12">
		<span>{{ url }}</span>
	</div>

<script src="jquery.min.js"></script>
<script src="angular.min.js"></script>
<script src="bootstrap.min.js"></script>
<script src="qr.min.js"></script>
<script src="qrcode.min.js"></script>
<script type="text/javascript">
 angular.module('textInputExample', [])
    .controller('QRController', ['$scope', function($scope) {
      $scope.change = function() {
		qr.canvas({ 
			canvas: document.getElementById('neocotic-qr-code'), 
			value: $scope.url,
			level: 'H', size: 10
		});
      };

      $scope.url = '<?= trim($otpauth) ?>';
	$scope.change();
    }]);
</script>
</body>
</html>
