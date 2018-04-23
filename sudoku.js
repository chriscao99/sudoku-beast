$(document).ready(function(){
    function populate(ps) {
        $("input.numberInput").css({opacity: 0});
        setTimeout(function() {
            for(i=0;i<9;i++) {
                var row = ".r"+i;
                for(j=0;j<9;j++) {
                    var cell = ".c"+j;
                    var chr = ps.charAt(i*9 + j);
                    $(row+" "+cell).val( (chr != '.' && chr != ' ') ? chr : '');
                }
            }
            $("input.numberInput").css({opacity: 1});
        }, 400);
    }
    function getstr() {
        var str = "";
        for(i=0;i<9;i++) {
            var row = ".r"+i;
            for(j=0;j<9;j++) {
                var cell = ".c"+j;
                var $cj = $(row+" "+cell);
                if($cj.val().match(/^\d$/)) {
                    str+=$cj.val();
                } else {
                    str+="."
                }
            }
        }
        return str;
    }
    $("#clear").click(function() {
        populate("         ".replace(/ /g,'         '));
    });
    $("#submit").click(function(){
        $(this).css({color:'black'})
        str = getstr();
        // if(str.replace(/\./g,'') == "") return;
        $("#loading").css({opacity:1});
        $.ajax({
            type: "POST",
            url: "solve.php",
            data: { b:str },
            success: function(d) {
                $("#loading").css({opacity:0});
                if(d.startsWith("500") || d.startsWith("400") || d.length != 81) {
                    $("#submit").css({color: 'red'});
                    setTimeout(function() {
                        $("#submit").css({color: 'black'});
                    }, 300);
                }
                else {
                    populate(d);
                }
            }
        });
    });

    // forceNumeric() plug-in implementation
    $.fn.forceNumeric = function () {
        return this.each(function () {
            $(this).keydown(function (e) {
                var key = e.which || e.keyCode;
                if (!e.shiftKey && !e.altKey && !e.ctrlKey &&
                // numbers
                    key >= 49 && key <= 57 ||
                // Numeric keypad
                    key >= 96 && key <= 105 ||
                // Backspace and Tab and Enter and Delete
                    key == 8 || key == 9 || key == 13 || key == 46)
                        return true;
                return false;
            });
        });
    }

    $(".numberInput").forceNumeric();
    $("#submitimage").click(function(e){
        $("#loadingocr").css({opacity:1});
        console.log("OCR requested");
        e.preventDefault();
        var data = new FormData();
        data.append('sudoku',$("#image")[0].files[0]);
        $.ajax({
            url: "image.php",
            type: "POST",
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                $("#loadingocr").css({opacity:0});
                console.log("Repopulating...");
                var clean = data.replace(/([ ]|\n)/g,'').replace(/0/g,' ');
                if(clean.length == 81) {
                    populate(clean);
                } else {
                    $("#submitimage").css({color:'red'});  
                    setTimeout(function() {
                        $("#submitimage").css({color: 'black'});
                    }, 300);
                }
            },
            error: function(data) {
                $("#submitimage").css({color:'red'});  
                setTimeout(function() {
                    $("#submitimage").css({color: 'black'});
                }, 300);
            }
        });
    });

    // FILE INPUT
    var fileInputTextDiv = document.getElementById('file_input_text_div');
    var fileInput = document.getElementById('image');
    var fileInputText = document.getElementById('file_input_text');
    fileInput.addEventListener('change', changeInputText);
    fileInput.addEventListener('change', changeState);

    function changeInputText() {
        var str = fileInput.value;
        var i;
        if (str.lastIndexOf('\\')) {
            i = str.lastIndexOf('\\') + 1;
        } else if (str.lastIndexOf('/')) {
            i = str.lastIndexOf('/') + 1;
        }
        fileInputText.value = str.slice(i, str.length);
        $("label[for='file_input_text']").text("");
    }
    function changeState() {
        if (fileInputText.value.length != 0) {
            if (!fileInputTextDiv.classList.contains("is-focused")) {
                fileInputTextDiv.classList.add('is-focused');
            }
        } else {
            if (fileInputTextDiv.classList.contains("is-focused")) {
                fileInputTextDiv.classList.remove('is-focused');
            }
        }
    }
    function getSharable() {
        return (""+document.location).split("?")[0] + "?b=" + getstr();
    }
    $("#share").click(function() {
        $("#slink").val(getSharable()).addClass("is-dirty");
        $("#shareable_link").addClass("is-dirty");
    });

    $.urlParam = function(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null) {
           return null;
        }
        else {
           return results[1] || 0;
        }
    }
    if($.urlParam("b")) {
        populate($.urlParam("b"));
    } else {
        populate(".8..4....3......1........2...5...4.69..1..8..2...........3.9....6....5.....2.....");
    }
});
