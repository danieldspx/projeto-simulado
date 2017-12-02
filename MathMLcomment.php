<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <script src="http://www.wiris.net/demo/editor/editor"></script>
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <script src="js/jquery-1.11.1.min.js"></script>
    <style>
        .btn {
        background: #3498db;
        background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
        background-image: -moz-linear-gradient(top, #3498db, #2980b9);
        background-image: -ms-linear-gradient(top, #3498db, #2980b9);
        background-image: -o-linear-gradient(top, #3498db, #2980b9);
        background-image: linear-gradient(to bottom, #3498db, #2980b9);
        -webkit-border-radius: 7;
        -moz-border-radius: 7;
        border-radius: 7px;
        font-family: Arial;
        color: #ffffff;
        font-size: 18px;
        padding: 10px 20px 10px 20px;
        text-decoration: none;
        border: none;
        }

        .btn:hover {
        background: #3cb0fd;
        background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
        background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
        background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
        background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
        background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
        text-decoration: none;
        }
    </style>
    <script>
        var editor;
        window.onload = function () {
        editor = com.wiris.jsEditor.JsEditor.newInstance({'language': 'pt_br', 'toolbar' : 'evaluate'});
        editor.insertInto(document.getElementById('editorContainer<?php echo $_GET['nquestao']; ?>'));
        }
    </script>
    <script language='JavaScript1.2'>
        function disableselect(e){
        return false
        }
        function reEnable(){
        return true
        }
        //if IE4+
        document.onselectstart=new Function (&quot;return false&quot;)
        //if NS6
        if (window.sidebar){
        document.onmousedown=disableselect
        document.onclick=reEnable
        }
    </script>
  </head>
  <body>
    <div oncopy="return false" id="editorContainer<?php echo $_GET['nquestao']; ?>"></div>
    <br>
    <button class="btn" id="buttonCopy">Copiar Código <i class="fa fa-files-o" aria-hidden="true"></i></button>
    <button style="display: none;" id="btnAccess" onclick="document.getElementById('buttonCopy').click()"></button>
    <script src="js/clipboard.min.js"></script>
    <script>
        var latexFinal="oi";
        var botao = document.getElementById('btnAccess');
        var contador = 0;
        function convertLatex(){
            var page = "http://www.wiris.net/demo/editor/mathml2latex";
            var mml = editor.getMathML();
            $.ajax
            ({
                type: 'POST',
                dataType: 'html',
                url: page,
                data: {mml: mml},
                async: false,
                success: function (msg)
                {
                    latexFinal = "[text]"+msg+"[/text]"; 
                }
            });
        }
        var clipboard = new Clipboard('.btn', {
        text: function() {
            convertLatex();
            return latexFinal;
        }
        });
    
        clipboard.on('success', function(e) {
            console.log(e);
        });

        clipboard.on('error', function(e) {
            console.log(e);
        });
        
    </script>
  </body>
</html>
