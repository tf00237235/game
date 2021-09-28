function show_travel() {
    var dialog_msg = document.getElementById("dialog");
    var role_id = document.getElementById("role_id").value;
    dialog_msg.innerHTML =
        '<form name="form" id="form">' +
        '<input type="hidden" name="type" id="type" value="">' +
        '<input type="hidden" name="role_id" value="' + role_id + '">' +
        '<table width=100% style="table-layout: fixed"><tbody><tr id="tr"><tr id="tr_illustrate" style="height:200">';
    // 一輪一年，共4季
    for (var i = 0; i < 4; i++) {
        document.getElementById("tr").innerHTML +=
            '<td  align="center">' +
            '<select onchange="javascript:change_illustrate(' + i + ')" name="travel_' + i + '" id="travel_' + i + '" style="color:#000">' +
            '<option value="" style="color:#000">第' + (i + 1) + '季</option>' +
            '<option value="0" style="color:#000">冒險</option>' +
            '<option value="1" style="color:#000">訓練</option>' +
            '<option value="2" style="color:#000">休息</option>' +
            '<option value="3" style="color:#000">挑戰</option>' +
            '</select>';
        document.getElementById("tr_illustrate").innerHTML +=
            '<td  align="center">' +
            '<div id="show_travel_illustrate_' + i + '" style="color:#000"></div>';
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

function change_illustrate(travel_illustrate_box) {
    var option_value = document.getElementById("travel_" + travel_illustrate_box).value;

    switch (option_value) {
        case "0":
            document.getElementById("show_travel_illustrate_" + travel_illustrate_box).innerHTML = "隨機的難度！";
            break;
        case "1":
            document.getElementById("show_travel_illustrate_" + travel_illustrate_box).innerHTML =
                "進行訓練<br>訓練有沒有效、訓練哪個能力一切隨機。";
            break;
        case "2":
            document.getElementById("show_travel_illustrate_" + travel_illustrate_box).innerHTML =
                "隨機產生3~10個事件<br>好壞各安天命。";
            break;
        case "3":
            document.getElementById("show_travel_illustrate_" + travel_illustrate_box).innerHTML =
                "不建議剛創角就去打！";
            break;
    }

}

function save_travel(form) {
    if (form.travel_0.value == '' || form.travel_1.value == '' || form.travel_2.value == '' || form.travel_3.value == '') {
        alert("一定要選擇出行程");
    } else {
        form.type.value = 'travel_save';
        form.method = 'post';
        form.action = "./home.php";
        form.submit();
    }
}