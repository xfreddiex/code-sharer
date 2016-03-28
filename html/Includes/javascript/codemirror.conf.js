 var editor = null;

$(document).ready(function(){
    $("#code").each(function(){
        CodeMirror.modeURL = "/Includes/codemirror/mode/%N/%N.js";

        editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            lineNumbers: true,
            theme: "elegant",
            readOnly: true,
            viewportMargin: Infinity
        });

        var mode = "clike";
        var mime = "text/plain";
        var info = CodeMirror.findModeByExtension($("#code").attr("lang"));
        if(info){
          mode = info.mode;
          mime = info.mime;
        }
        editor.setOption("mode", mime);
        CodeMirror.autoLoadMode(editor, mode);
        $("#code").attr("mime-type", mime);
    });
});