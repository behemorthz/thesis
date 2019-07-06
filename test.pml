mtype ={idle ,running ,done}
/*---------- variable of StateG--------------*/
mtype stateStatusG= idle
bool preFailG= false
bool postFailG= false
/*---------- variable of StateE--------------*/
mtype stateStatusE= idle
bool preFailE= false
bool postFailE= false
/*---------- variable of StateD--------------*/
mtype stateStatusD= idle
bool preFailD= false
bool postFailD= false
/*---------- variable of StateC--------------*/
mtype stateStatusC= idle
bool preFailC= false
bool postFailC= false
/*---------- variable of StateB--------------*/
mtype stateStatusB= idle
bool preFailB= false
bool postFailB= false
/*---------- variable of StateA--------------*/
mtype stateStatusA= idle
bool preFailA= false
bool postFailA= false
bit Terminate = 0 ;
int x= 1;
bool ga= false;
bool gd= false
bool gc= false
bool ge= false
bool gdc= false
proctype StateG() {
	precon : goto GOperation ;
	GOperation :
		atomic{
			stateStatusG = running;
			goto  postcon ;
		}
	postcon :stateStatusG= done
}

proctype StateE() {
	precon : goto EOperation ;
	EOperation :
		atomic{
			stateStatusE = running;
			goto  postcon ;
		}
	postcon :stateStatusE= done
}

proctype StateD() {
	precon : goto DOperation ;
	DOperation :
		atomic{
			stateStatusD = running;
			goto  postcon ;
		}
	postcon :stateStatusD= done
}

proctype StateC() {
	precon : goto COperation ;
	COperation :
		atomic{
			stateStatusC = running;
			goto  postcon ;
		}
	postcon :stateStatusC= done
}

proctype StateB() {
	precon : goto BOperation ;
	BOperation :
		atomic{
			stateStatusB = running;
			goto  postcon ;
		}
	postcon :stateStatusB= done
}

proctype StateA() {

stateStatusA = running;

	precon:

		if
		::(x>0)->   goto AOperation ;
		::(x<=0)->preFailA=true ;Terminate = 1 ;
		fi ;

	AOperation:
		atomic{
			stateStatusA = running;
    		ga =true;
                                

			/*....Fill in details of operation....*/
			goto  postcon ;
		}
	postcon:
                             if
                             ::x =1001;
                             ::x=100;
                             fi;
                             
		if
		::(x>=1000)-> stateStatusA= done
		::(x<1000)->postFailA=true ;Terminate = 1 ;
		fi ;

}

proctype FinalState() {
	 Terminate = 1;
}
active proctype checkInVariant() {
	do
	:: assert(Terminate==0);
	od ;
}
init {
A:
	if
	::(stateStatusA== idle)->run StateA( )
	::(stateStatusA== done)->
		if
 		::(ga==true)-> stateStatusA=idle ;  goto  B
 		::(ga==false)-> stateStatusA=idle ;  goto  A
 		fi
	fi;
	goto  A;
B:
	if
	::(stateStatusB== idle)->run StateB( )
	::(stateStatusB== done)->stateStatusB=idle ;
	goto  C
 	fi;
	goto  B;
C:
	if
	::(stateStatusC== idle)->run StateC( )
	::(stateStatusC== done)->
		if
 		::(gd==false)-> stateStatusC=idle ;  goto  D
 		::(gc==true)-> stateStatusC=idle ;  goto  E
 		fi
	fi;
	goto  C;
D:
	if
	::(stateStatusD== idle)->run StateD( )
	::(stateStatusD== done &&gd==true)->
		if
 		::(gdc==true)-> stateStatusD=idle ;  goto  G
 		fi
	fi;
	goto  D;
E:
	if
	::(stateStatusE== idle)->run StateE( )
	::(stateStatusE== done &&ge==true)->
		if
 		::(gdc==true)-> stateStatusE=idle ;  goto  G
 		fi
	fi;
	goto  E;
G:
	if
	::(stateStatusG== idle)->run StateG( )
	::(stateStatusG== done)->stateStatusG=idle ;
 	run FinalState()
 	fi;
	goto  G;
}
