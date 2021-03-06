<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Ander's Kitchen | Scheduling Helper</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/foundation.css" /> <!--foundation styles-->
        <link rel="stylesheet" href="css/ownerPortal.css" />
        <link rel="stylesheet" href="css/foundation-datepicker.css" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" />
        <link href="https://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">

        <?php
        require_once './httpful.phar';
        include 'PHPHelpers/permissionPolicer.php';
        $permission = permissionPolicer();
        ?>
    </head>

    <body>
        <?php
        include "config.php";
        if ($permission == "False") {
            ?>
            You Really Shouldn't Be Here.. go to: https://www.anderskitchen.com/login.php to log in.
            <?php
        } else {
            ?>
            <?php
            $data = array(
                'recipe_id' => $_GET["recipe_id"],
                'recipeName' => $_GET["recipeName"],
                'pictureURL' => $_GET["pictureURL"],
                'recipeURL' => $_GET["recipeURL"]
            );

            $url = $data['recipeURL'];
            $html = file_get_contents($url);
            $string = "Cost per Serving:";
            if (strpos($html, $string) !== false) {
                $costPerServingWithSign = substr($html, strpos($html, $string) + strlen($string), 8);
                $costPerServing = substr($costPerServingWithSign,strpos($costPerServingWithSign, "$")+1, strpos($costPerServingWithSign, "<")-2);
                $salePricePerServing = round($costPerServing*$profitMultiplier,0,PHP_ROUND_HALF_UP);
            } else {
                $costPerServingWithSign = "Error: Price per serving not Found";
                $costPerServing = "999.99";
                $salePricePerServing = round($costPerServing*$profitMultiplier,0,PHP_ROUND_HALF_UP);
            }
            ?>
    <center><h1>Save or Schedule!</h1></center>
            <br>
            <h2>Save this recipe</h2>
            <form action="https://www.anderskitchen.com/PHPHelpers/saveRecipe.php" method="post">
                <center>
                    <?php //var_dump($data['misc']); ?>
                    <input type="hidden" name="recipe_id" id="recipe_id" value="<?php echo $data['recipe_id']; ?>">
                    <input type="hidden" name="recipeName" id="recipeName" value="<?php echo $data['recipeName']; ?>">
                    <input type="hidden" name="pictureURL" id="pictureURL" value="<?php echo $data['pictureURL']; ?>">
                    <input type="hidden" name="recipeURL" id="recipeURL" value="<?php echo $data['recipeURL']; ?>">
                    <input type="hidden" name="costPerServing" id="costPerServing" value="<?php echo $costPerServing; ?>">
                    <input type="hidden" name="salePricePerServing" id="salePricePerServing" value="<?php echo $salePricePerServing; ?>">
                    <input class="button" name="saveRecipe" id="saveRecipe" type="submit" value="Save this recipe">
                </center>
            </form>
            <hr>
            <h2>Schedule<h2>
            <h3>You can make this meal for roughly <?php echo $costPerServingWithSign;?></h3>
            <h3>With your settings, you'll sell it for $<?php echo $salePricePerServing;?>.00</h3>
            <p class="lead">Pick the date of when you want to schedule this meal! Then, click schedule!</p>
            <form action="https://www.anderskitchen.com/PHPHelpers/addToMenu.php" method="post">
                <center>
                    <?php //var_dump($data['misc']); ?>
                    <input type="text" class="span2" id="dp1" name="dp1">
                    <input type="hidden" name="recipe_id" value="<?php echo $data['recipe_id']; ?>">
                    <input type="hidden" name="recipeName" value="<?php echo $data['recipeName']; ?>">
                    <input type="hidden" name="pictureURL" value="<?php echo $data['pictureURL']; ?>">
                    <input type="hidden" name="recipeURL" value="<?php echo $data['recipeURL']; ?>">
                    <input type="hidden" name="costPerServing" id="costPerServing" value="<?php echo $costPerServing; ?>">
                    <input type="hidden" name="salePricePerServing" id="salePricePerServing" value="<?php echo $salePricePerServing; ?>">
                    <input class="button" name="addToTheMenu" type="submit" value="Schedule">
                </center>
            </form>
        </div>
    <?php } ?>

    <script type="text/javascript" src="js/vendor/jquery.js"></script>
    <script type="text/javascript" src="js/vendor/foundation.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script> <!---My custom jscript and jquery--->
    <script type="text/javascript" src="js/foundation-datepicker.js"></script>
    <script>
        $(function () {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!    
            var yyyy = today.getFullYear();

            if (dd < 10) {
                dd = '0' + dd
            }

            if (mm < 10) {
                mm = '0' + mm
            }

            var todaysdate = '' + yyyy + '-' + mm + '-' + dd;
            $('#dp1').fdatepicker({
                initialDate: todaysdate,
                format: 'yyyy-mm-dd',
                disableDblClickSelection: true,
                leftArrow: '<<',
                rightArrow: '>>',
                closeIcon: 'X',
                closeButton: true
            });
        });
    </script>
    <script type="text/javascript">
    </script>
</body>
</html>
