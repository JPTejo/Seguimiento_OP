<?php include "conexion.php"; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario Wizard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background: #f7f7f7;
            padding-top: 50px;
        }
        .wizard > .content {
            min-height: 300px;
        }
        .wizard-big .content > .body {
            padding: 30px;
        }
    </style>
</head>
<body>

<div class="container">
    <form id="form" action="#" class="wizard-big">
        <h1>Account</h1>
        <fieldset>
            <h2>Account Information</h2>
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-group">
                        <label>Username *</label>
                        <input id="userName" name="userName" type="text" class="form-control required">
                    </div>
                    <div class="form-group">
                        <label>Password *</label>
                        <input id="password" name="password" type="password" class="form-control required">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password *</label>
                        <input id="confirm" name="confirm" type="password" class="form-control required">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="text-center">
                        <div style="margin-top: 20px">
                            <i class="fa fa-sign-in-alt" style="font-size: 180px;color: #e5e5e5 "></i>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <h1>Profile</h1>
        <fieldset>
            <h2>Profile Information</h2>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>First name *</label>
                        <input id="name" name="name" type="text" class="form-control required">
                    </div>
                    <div class="form-group">
                        <label>Last name *</label>
                        <input id="surname" name="surname" type="text" class="form-control required">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Email *</label>
                        <input id="email" name="email" type="email" class="form-control required">
                    </div>
                    <div class="form-group">
                        <label>Address *</label>
                        <input id="address" name="address" type="text" class="form-control">
                    </div>
                </div>
            </div>
        </fieldset>

        <h1>Warning</h1>
        <fieldset>
            <div class="text-center" style="margin-top: 120px">
                <h2>You did it Man :-)</h2>
            </div>
        </fieldset>

        <h1>Finish</h1>
        <fieldset>
            <h2>Terms and Conditions</h2>
            <input id="acceptTerms" name="acceptTerms" type="checkbox" class="required"> 
            <label for="acceptTerms">I agree with the Terms and Conditions.</label>
        </fieldset>
    </form>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>

<script>
$(document).ready(function () {
    var form = $("#form");

    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.before(error);
        },
        rules: {
            confirm: {
                equalTo: "#password"
            }
        }
    });

    form.steps({
        headerTag: "h1",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        autoFocus: true,
        onStepChanging: function (event, currentIndex, newIndex) {
            return form.valid();
        },
        onFinishing: function (event, currentIndex) {
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            alert("Formulario completado correctamente.");
        }
    });
});
</script>

</body>
</html>
