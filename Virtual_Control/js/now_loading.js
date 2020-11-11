/* 
 * [Loading Module]
 * The SVG Animation is Modified!
 */

function dispLoading() {
    var msg = '<div id="loading">'
            + '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="loadingMsg">'
            + '<rect x="17.5" y="30" width="15" height="40" fill="#ff5a00">'
            + '<animate attributeName="y" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="18;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.2s"></animate>'
            + '<animate attributeName="height" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="64;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.2s"></animate>'
            + '</rect>'
            + '<rect x="42.5" y="30" width="15" height="40" fill="#fff">'
            + '<animate attributeName="y" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="20.999999999999996;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.1s"></animate>'
            + '<animate attributeName="height" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="58.00000000000001;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1" begin="-0.1s"></animate>'
            + '</rect>'
            + '<rect x="67.5" y="30" width="15" height="40" fill="#bb5a00">'
            + '<animate attributeName="y" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="20.999999999999996;30;30" keySplines="0 0.5 0.5 1;0 0.5 0.5 1"></animate>'
            + '<animate attributeName="height" repeatCount="indefinite" dur="1s" calcMode="spline" keyTimes="0;0.5;1" values="58.00000000000001;40;40" keySplines="0 0.5 0.5 1;0 0.5 0.5 1"></animate>'
            + '</rect>'
            + '</svg>'
            + '</div>';
    if($("#loading").length === 0) {
        $("body").append(msg);
        $("#loading").fadeOut(0);
    }
    $("#loading").fadeIn(800);
}

function removeLoading() {
    $("#loading").remove();
}