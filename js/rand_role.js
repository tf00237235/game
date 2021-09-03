function role_ethnicity() {
    $.ajax({
        type: "post",
        url: "./home.php",
        data: {
            type: "role_ethnicity",
            role_id: document.getElementById("role_id").value,
        },
        datatype: "json",
        success: function(result) {
            result = JSON.parse(result);
            if (result.god >= 960000) {
                document.getElementById("dialog").innerHTML = "上帝一時興起，決定調整骰子的點數";
                $("#dialog").dialog({
                    buttons: {
                        "Ok": function() {
                            $(this).dialog("close");
                        }
                    },
                    modal: true,
                    closeOnEscape: false,
                    dialogClass: "dlg-no-close",
                    title: "上帝出手了！",
                });
            }
            var num = document.getElementById("role_num").innerHTML;
            document.getElementById("role_num").innerHTML = Number(num) + 1;
            document.getElementById("role").value = result.ethnicity;
            document.getElementById("str").value = 0;
            document.getElementById("agi").value = 0;
            document.getElementById("int").value = 0;
            document.getElementById("con").value = 0;
            document.getElementById("vit").value = 0;
            document.getElementById("role_id").value = result.role_id;
            //document.getElementById("role_num_display").innerHTML = "  (第" + num + "次骰)"
        },
    });
}

function role_ability() {
    if (document.getElementById("role").value == '深淵') {
        document.getElementById("dialog").innerHTML = "當你在凝視深淵的時候，深淵也在凝視你";
        $("#dialog").dialog({
            buttons: {
                "Ok": function() {
                    $(this).dialog("close");
                }
            },
            modal: true,
            closeOnEscape: false,
            dialogClass: "dlg-no-close",
            title: "去骰種族啦！",
        });
    } else {
        $.ajax({
            type: "post",
            url: "./home.php",
            data: {
                type: "role_ability",
                ethnicity: document.getElementById("role").value,
                role_id: document.getElementById("role_id").value,
            },
            datatype: "json",
            success: function(result) {
                result = JSON.parse(result);
                document.getElementById("str").value = result.str;
                document.getElementById("agi").value = result.agi;
                document.getElementById("int").value = result.int;
                document.getElementById("con").value = result.con;
                document.getElementById("vit").value = result.vit;
            },
        });
    }
}