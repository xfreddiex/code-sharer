$(document).ready(function(){
    CodeMirror.modeURL = "/Includes/codemirror/mode/%N/%N.js";

    var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        lineNumbers: true,
        theme: "elegant",
        indentUnit: 4,
        readOnly: true,
        viewportMargin: Infinity
    });

    var mode = "clike";
    var spec = "clike";
    var info = CodeMirror.findModeByExtension($("#code").attr("lang"));
    if(info){
      mode = info.mode;
      spec = info.mime;
    }
    editor.setOption("mode", spec);
    CodeMirror.autoLoadMode(editor, mode);
});