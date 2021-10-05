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
        data = JSON.parse(result);
        const talent = new Type(data, 30);
        talent.typingEffect();
    },
});

function behavior(behavior_type) {
    for (var i = 0; i < 7; i++) {
        document.getElementById("footer_behavior_" + i).href = "#";
    }
    var num = document.getElementById("instruction_num").value;
    document.getElementById("instruction_num").value = Number(num) + 1;
    if (behavior_type != 6) {
        $.ajax({
            type: "post",
            url: "./home.php",
            data: {
                type: "travel_instruction",
                role_id: document.getElementById("role_id").value,
                maze_id: document.getElementById("maze_id").value,
                instruction_num: document.getElementById("instruction_num").value,
                monster: document.getElementById("monster").value,
                behavior_type: behavior_type,
            },
            datatype: "json",
            success: function(result) {
                console.log(result);
                strings = JSON.parse(result);
                const talent = new Type(strings, 30);
                talent.typingEffect();
            },
        });
    } else {
        $.ajax({
            type: "post",
            url: "./home.php",
            data: {
                type: "leave_maze",
                role_id: document.getElementById("role_id").value,
                maze_id: document.getElementById("maze_id").value,
            },
            datatype: "json",
            success: function(result) {
                console.log(result);
                strings = JSON.parse(result);
                const talent = new Type(strings, 30);
                talent.typingEffect();
                setTimeout(function() {
                    var form = document.getElementById("form");
                    console.log(form);
                    form.type.value = 'travel_save';
                    form.method = 'post';
                    form.action = "./home.php";
                    form.submit();
                }, 10000);
            },
        });
    }
}

function change_footer() {
    for (var i = 0; i < 7; i++) {
        document.getElementById("footer_behavior_" + i).href = "javascript:behavior('" + i + "')";
    }
}