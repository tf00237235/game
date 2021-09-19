function show_talent() {
    $.ajax({
        type: "post",
        url: "./home.php",
        data: {
            type: "get_talent_detail",
            role_id: document.getElementById("role_id").value,
            detail: "1",
        },
        datatype: "json",
        success: function(result) {
            document.getElementById("dialog").innerHTML = "";
            document.getElementById("dialog").innerHTML = result;
            $("#dialog").css({ overflow: "auto" }).dialog({
                width: "70%",
                height: "450",
                buttons: {
                    "關閉": function() {
                        $(this).dialog("close");
                    }
                },
                modal: true,
                closeOnEscape: false,
                draggable: false,
                dialogClass: "dlg-no-close",
                title: "天賦",
            });
        },
    });
}

function btn_show_talent(id) {
    $.ajax({
        type: "post",
        url: "./home.php",
        data: {
            type: "get_talent_detail",
            talent_id: id,
            detail: "0",
        },
        datatype: "json",
        success: function(result) {
            document.getElementById("show_talent").innerHTML = "";
            result = JSON.parse(result);
            document.getElementById("show_talent").innerHTML =
                "天賦名：" + result.Name + "<br>" +
                "簡介：" + result.synopsis + "<br>" +
                "能力：" + result.ability + "<br>" +
                "副作用：" + result.side_effect + "<br>" +
                "取得方式：" + result.obtain + "<br>";
        },
    });
}