
{
	split($0,sp,",");
	
	count_col=0;
	
	line="";
	
	for(i=1;i<=col_max;i++){
		
		if(count_col==col && $1==row){
			line=line""value",";
		}
		else if(sp[i]){
			line=line""sp[i]",";
		}
		else{
			line=line",";
		}
		count_col++;
	}
	print line;
	
	#print col"/"row"/"line;
	
	#if(count_col==col && count_row==row){sp[2]=value}
	#print $0" / "count_col" / "count_row" / "sp[1]","sp[2]","sp[3];
	#print $1","$2","$3;
	#echo line >> ini".tmp";
}

#END{
	#print "---";
	#print value;
	#print col;
	#print row;
	
#}