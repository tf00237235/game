let strings = [{
        name: 'system',
        content: "",
    },
    {
        name: '',
        content: "【 迷宮創建中 】",
    },
    {
        name: '',
        content: "【 怪物生成中 】",
    },
];

const type = new Type(strings, 30)
type.typingEffect_no_change_footer();


$.ajax({
    type: "post",
    url: "./home.php",
    data: {
        type: "maze_monster_creat",
        role_id: document.getElementById("role_id").value,
        maze_id: document.getElementById("maze_id").value,
    },
    datatype: "json",
    success: function(result) {
        console.log(result);
        data = JSON.parse(result);
        const talent = new Type(data, 30);
        talent.typingEffect();
    },
});

function behavior(behavior_type) {
    change_footer();
    $.ajax({
        type: "post",
        url: "./home.php",
        data: {
            type: "travel_instruction",
            role_id: document.getElementById("role_id").value,
            behavior_type: behavior_type,
        },
        datatype: "json",
        success: function(result) {
            strings = JSON.parse(result);
            const talent = new Type(strings, 30);
            talent.typingEffect();
        },
    });
}

function change_footer() {
    if (document.getElementById("footer_behavior_0").href != '#') {
        for (var i = 0; i < 7; i++) {
            document.getElementById("footer_behavior_" + i).href = "javascript:behavior('" + i + "')";
        }
    } else {
        for (var i = 0; i < 7; i++) {
            document.getElementById("footer_behavior_" + i).href = "#";
        }
    }
}