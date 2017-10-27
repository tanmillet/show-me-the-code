#include<stdio.h>
#include<string.h>

int global = 1;
extern int lucasAbc;

void func1(void);
void func2(int expr);
void funcArr(int[]);

static int count = 10;

#define TRUE 1;
#define FALSE 0;

//结构体
typedef struct Books {
    char title[50];
    char author[50];
    char subject[100];
    int  book_id;
};

//共用体
union Data {
    int i;
    float f;
    char str[9];
    double d;
} data;

//位域结构体
struct {
    unsigned int age : 3; //000 ------ 0 //111 --------- 7
} Age;

int main(void) {

    //题目：有1、2、3、4个数字，能组成多少个互不相同且无重复数字的三位数？都是多少？
    int i, j, k;
    for(i = 1; i < 5; i++) {
        for(j = 1; j < 5; j++) {
            if(i == j) { continue; }
            for(k = 1; k < 5; k++) {
                if(i == k || j == k) { continue; }
                //if (i != k && i != j && j != k) {
                //    printf("%d,%d,%d\n", i, j, k);
                //}

                  printf("%d,%d,%d\n", i, j, k);
            }
        }
    }

    return 0;

    //while (count--) {
    //    func1();
    //}
    //func2(2);

    //int arr[10] = {112321,2,3,4,5,6,7,8,9,10};
    //funcArr(arr);


    //结构体
    struct Books book1;

    strcpy(book1.title, "lucas books");
    strcpy(book1.author, "tan");
    strcpy(book1.subject, "millet");
    book1.book_id = 70000;

    printf("Book1.title : %s\n", book1.title);

    //共用体
    union Data data;

    data.i = 100;
    printf("Book1.title : %d\n", data.i);
    data.f = 23.45;
    printf("Book1.title : %f\n", data.f);
    strcpy(data.str, "Cssssssssssss");

    printf("memory size occupied by data : %d\n", sizeof(data));
    printf("Book1.title : %s\n", data.str);


    //位域结构体

    Age.age = 3;

    printf("Sizeof (Age) : %d\n", sizeof(Age));
    printf("Age.age : %d\n", Age.age);

    Age.age = 7;

    printf("Sizeof (Age) : %d\n", sizeof(Age));
    printf("Age.age : %d\n", Age.age);


    Age.age = 8; //1000

    printf("Sizeof (Age) : %d\n", sizeof(Age));
    printf("Age.age : %d\n", Age.age);



    return 0;
    //printf("hello world! --- %d" , 1+global);
    //return 0;
}

void func1() {
    static int thingy = 5;
    thingy++;
    printf(" thingy : %d ， count : %d\n", thingy, count);
}

void func2(int expr) {
    switch(expr) {
    case 2:
        printf("the expr value is 2 :  %d", expr);
        break;
    default:
        printf("the expr value is not 2");
    }
}

void funcArr(int score[]) {
    int y;
    for(y = 0; y < 10; y++) {
        printf(" y value: %d", score[y]);
    }
}


