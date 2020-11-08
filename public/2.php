<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body>
<div class="container">

    <form id="ExampleForm" action="form-handler.php" method="get">

        <?php for($i =1; $i <= 5; $i++) : ?>
            <div class="form-group">
                <label for="Select<?=$i?>">Example select <?=$i?></label>
                <select class="form-control" id="Select<?=$i?>" name="select_<?=$i?>">
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                </select>
            </div>

        <?php endfor; ?>

        <div class="form-group">
            <label for="FirstTextField">First Text Field</label>
            <input type="text" class="form-control" id="FirstTextField" placeholder="Enter first value" name="text_1">
        </div>

        <div class="form-group">
            <label for="SecondTextField">Second Text Field</label>
            <input type="text" class="form-control" id="SecondTextField" placeholder="Enter second value" name="text_2">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <br/>
    <br/>
    <div id="FormResult"></div>

</div>
</body>
<script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
<script>

    $('#ExampleForm').on('submit', function(e){
        e.preventDefault();
        var resultBlock = $('#FormResult');
        var code = resultBlock.find('code');
        if(!code.length) {
            code = $('<code/>');
            resultBlock.append(code);
        }
        var data = $(this).serializeArray();
        code.html(JSON.stringify(data));

        $.get('form-handler.php', data, function(response){
            alert(response);
        });

    });

</script>
</html>
