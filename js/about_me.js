function about_me() {
    document.getElementById("dialog_about_me").innerHTML = "你好！我是作者Nal、菜B8或者B8<br>" +
        "謝謝你遊玩這個遊戲，雖然可能玩起來有點無趣，但實際上這只一個作品集的概念加上discord 群內腦洞大開的想法<br>" +
        "由於我製作時考慮到未來擴容以及增加遊玩性的方式，所以版本是會更新的，前提是我有新想法或是有人提供了新想法<br>" +
        "當前版本：V1.0<br>" +
        "感謝： <br>Chart.js：www.chartjs.org/ (六角圖模組)<br>" +
        "Huli：https://github.com/aszx87410 (整體模板+文字顯示方式)<br>**另外請問有人有「Huli」的聯繫方式嗎？**<br>";

    $("#dialog_about_me").dialog({
        width: "70%",
        height: "450",
        buttons: {
            "感謝你": function() {
                $(this).dialog("close");
            }
        },
        modal: true,
        closeOnEscape: false,
        draggable: false,
        dialogClass: "dlg-no-close",
        title: "感謝你的遊玩！",
    });
}