function show_travel() {
    var dialog_msg = document.getElementById("dialog");
    dialog_msg.innerHTML =
        '<form name="form" id="form">' +
        '<input type="hidden" name="type" id="type" value="">';
    // 一輪一年，共4季
    for (var i = 0; i < 4; i++) {
        document.getElementById("form").innerHTML +=
            '<div class="travel_' + i + '">' +
            '<select name="travel_' + i + '" id="travel" style="color:#000">' +
            '<option value="2" style="color:#000">冒險(簡易)</option>' +
            '<option value="1" style="color:#000">冒險(普通)</option>' +
            '<option value="0" style="color:#000">冒險(困難)</option>' +
            '<option value="3" style="color:#000">城鎮訓練</option>' +
            '<option value="4" style="color:#000">城鎮休息</option>' +
            '</select>' +
            '<div id="show_travel_illustrate_' + i + '"></div>' +
            '</div>';
    }

    $("#dialog").css({ overflow: "auto" }).dialog({
        width: "70%",
        height: "450",
        buttons: {
            "出發": function() {
                var form = document.getElementById("form");
                save_travel(form);
            },
            "取消": function() {
                $(this).dialog("close");
            }
        },
        modal: true,
        closeOnEscape: false,
        draggable: false,
        dialogClass: "dlg-no-close",
        title: "行程",
    });

}

function save_travel(form) {
    form.type.value = 'travel_save';
    form.method = 'post';
    form.action = "./home.php";
    form.submit();
}