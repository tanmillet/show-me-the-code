require.config({
    baseUrl: "lib",
    paths: {
        "test": "test"
    }
})


require(['test'], function (test) {
    console.log(test.tip());
})