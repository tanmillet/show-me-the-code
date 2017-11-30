#include <stdio.h>

int main(void) {
    int a = 10;
    int b = 20;
    int maxNum = max(a, b);
    int minNum = min(a, b);
    printf("a b maxNum: %d\n", maxNum);
    printf("a b minNum: %d\n", minNum);
    return 0;
}
