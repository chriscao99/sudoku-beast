<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.blue_grey-red.min.css">
        <script defer src="https://code.getmdl.io/1.1.3/material.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="sudoku.js"></script>
        <style>
            input[type="text"] {
                width: 100%;
                height: auto;
                box-sizing: border-box;
                -webkit-box-sizing:border-box;
                -moz-box-sizing: border-box;
            }
            body {
                text-align: center;
                background-color: #EEE;
            }
            table {
                border-collapse: collapse;
                font-family: Calibri, sans-serif;
                background-color: white;
                margin: 0 auto;
                padding: 5px;
            }
            colgroup, tbody { border: solid medium; }
            td { border: solid thin; height: 3em; width: 3em; text-align: center; padding: 0; }
            input.numberInput {
                font-size:18pt;
                height:46px;
                text-align:center;
                opacity: 1;
                transition: opacity 300ms;
            }
            .solve {
                width: 100%;
                text-align: center;
            }
            #loading, #loadingocr {
                z-index: -100;
                opacity: 0;
                transition: opacity 300ms;
                margin-left: auto;
                margin-right: auto;
                width: 200px;
                top: 20px;
            }
            #submit, #clear, #submitimage {
                opacity: 1;
                transition: color 100ms;
            }
            tr.r2, tr.r5 {
                border-bottom: solid medium;
            }

            .file_input_div {
                margin: auto;
                width: 250px;
                height: 40px;
            }
            .file_input {
                float: left;
            }
            #file_input_text_div {
                width: 200px;
                margin-top: -8px;
                margin-left: 5px;
            }
            .none {
                display: none;
            }
            .upload,.shareable {
                margin-top: 30px;
                margin-bottom: 20px;
            }
            #shareable_link {
                width: 246px;
            }
            @media screen and (min-width: 1000px) {
                .shareable {
                    margin-right: 20%;
                    float:right;
                }
            }
            @media screen and (min-width: 1000px) {
                .upload {
                    margin-left: 20%;
                    float:left;
                }
            }

        </style>
        <title>Sudoku Solver</title>
    </head>
    <body>
        <h1>Sudoku Solver</h1>
        <h4>Enter numbers below or upload a picture of your sudoku puzzle.</h4>
        <br>
        <table>
        <colgroup><col><col><col>
        <colgroup><col><col><col>
        <colgroup><col><col><col>
        <tbody>
        <?php
            for($i = 0;$i<9;$i+=1) {
                echo("<tr class='r$i'>");
                for($j = 0;$j<9;$j+=1) {
                    echo("<td><input type='text' maxlength='1' class='numberInput c$j'></td>");
                }
                echo("</tr>");
            }
        ?>
        </tbody>
        </table>
        <br>

        <div class="solve">
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" style="font-weight:bold" id="clear">
                Clear
            </button>
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" style="font-weight:bold" id="submit">
                Solve
            </button>
            <!-- MDL Progress Bar with Indeterminate Progress -->
            <div id="loading" class="mdl-progress mdl-js-progress mdl-progress__indeterminate"></div>
        </div>
        <div class="extra" style="margin-bottom:40px;">
            <div class="upload">
                <form action="image.php" enctype="multipart/form-data" method="post">
                    <div class="file_input_div">
                        <div class="file_input">
                            <label class="image_input_button mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-js-ripple-effect mdl-button--colored">
                                <i class="material-icons" style="text-align:center">file_upload</i>
                                <input type="file" accept="image/*" name="sudoku" size="25" id="image" class="none">
                            </label>
                        </div>
                        <div id="file_input_text_div" class="mdl-textfield mdl-js-textfield textfield-demo">
                            <input class="file_input_text mdl-textfield__input" type="text" disabled readonly id="file_input_text" />
                            <label class="mdl-textfield__label" for="file_input_text">Upload a sudoku</label>
                        </div>
                    </div>
                    <br>
                    <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" style="font-weight:bold" id="submitimage">
                        Submit
                    </button>
                    <div id="loadingocr" class="mdl-progress mdl-js-progress mdl-progress__indeterminate"></div>
                </form>
            </div>
            <div class="shareable">
                <div id="shareable_link" class="mdl-textfield mdl-js-textfield">
                    <input class="file_input_text mdl-textfield__input" type="text" id="slink" />
                    <label class="mdl-textfield__label" for="slink">Shareable link</label>
                </div>
                <br>
                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" style="font-weight:bold" id="share">
                    Get link
                </button>
            </div>
        </div>
    </body>
</html>
