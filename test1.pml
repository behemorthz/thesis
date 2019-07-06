byte turn[2];
byte mutex;
proctype P(bit i){
    do
    ::  turn[i] = 1;
        turn[i] = turn[1-i]+1;
        (turn[1-i]==0)||(turn[i]<turn[1-i]);
        mutex++;
        printf("MSC: P(%d) has entered section.\n", i);
        mutex--;
        turn[i]=0;
    od
}
proctype monitor(){
    assert(mutex != 2);
}
init{
    atomic{
        run P(0);
        run P(1);
        run monitor();
    }
}