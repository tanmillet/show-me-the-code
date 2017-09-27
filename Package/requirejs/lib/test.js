define(['myLib'], function (myLib) {

    var tip = function () {
        return 'test' + myLib.tip()
    }

    return {
        tip: tip
    }
})