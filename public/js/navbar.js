function openNav(){
    $(".my-navbar").css("transition","500ms");
    $(".my-navbar").css("margin-top", "0px");
    $("body").css("overflow","hidden");
    demo();
}

function closeNav(){
    $(".my-navbar").css("transition","500ms");
    $(".my-navbar").css("margin-top", "calc(-100vh - 120px)");
    $("body").css("overflow","auto");
    demo();
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

async function demo() {
    await sleep(2000);
    $(".my-navbar").css("transition","0ms");
    $("#navigationbar").css("transition","0ms");


}

function lessNav(){
    $("#navigationbar").css("transition","500ms");
    $("#navigationbar").css("height","80px");
    $(".logo-container").css("filter","opacity(0)")
    demo();
}

function moreNav(){
    $("#navigationbar").css("transition","500ms");
    $(".logo-container").css("filter","opacity(1)")
    $("#navigationbar").css("height","120px");
    demo();
}