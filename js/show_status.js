function show_status() {
    $.ajax({
        type: "post",
        url: "./home.php",
        data: {
            type: "get_status",
            role_id: document.getElementById("role_id").value,
        },
        datatype: "json",
        success: function(result) {
            document.getElementById("dialog").innerHTML = "";
            result = JSON.parse(result);
            document.getElementById("dialog").innerHTML =
                "角色名：" + result.Name + " (" + result.eName + ")<br>" +
                "力量判定點數：" + result.str_dice_range.replace(",", " ~ ") + " (骰子數：" + result.str_dice_num + ")<br>" +
                "敏捷判定點數：" + result.dex_dice_range.replace(",", " ~ ") + " (骰子數：" + result.dex_dice_num + ")<br>" +
                "智慧判定點數：" + result.int_dice_range.replace(",", " ~ ") + " (骰子數：" + result.int_dice_num + ")<br>" +
                "體力判定點數：" + result.con_dice_range.replace(",", " ~ ") + " (骰子數：" + result.con_dice_num + ")<br>" +
                "體質判定點數：" + result.vit_dice_range.replace(",", " ~ ") + " (骰子數：" + result.vit_dice_num + ")<br>" +
                "攻擊骰子點數：" + result.atk_dice_range.replace(",", " ~ ") + " (骰子數：" + result.atk_dice_num + ")<br>" +
                "閃避骰子點數：" + result.dodge_dice_range.replace(",", " ~ ") + " (骰子數：" + result.dodge_dice_num + ")<br>" +
                "防禦骰子點數：" + result.def_dice_range.replace(",", " ~ ") + " (骰子數：" + result.def_dice_num + ")<br>" +
                "道具使用點數：" + result.goods_dice_range.replace(",", " ~ ") + " (骰子數：" + result.goods_dice_num + ")<br>" +
                "強化判定點數：0 ~ 100 (骰子數：" + result.strengthen_num + ")<br>" +
                "當最大點數小於最小點數時，最大點數自動調整成與最小點數相等。";
            $("#dialog").css({ overflow: "auto" }).dialog({
                width: "70%",
                height: "550",
                buttons: {
                    "關閉": function() {
                        $(this).dialog("close");
                    }
                },
                modal: true,
                closeOnEscape: false,
                draggable: false,
                dialogClass: "dlg-no-close",
                title: "狀態",
            });
        },
    });
}