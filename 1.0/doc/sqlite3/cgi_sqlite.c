/*
 * CGI for sqlite
 * vking
 * goto999@126.com
 *
 */
#include <sqlite3.h>
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <unistd.h>
#include <fcntl.h>
#include "cgi_sqlite.h"
int getCfgKey(char* key,char* value)
{
        char *buffer = readCfgFileX();
        char data[200];
        char *p=NULL,*p1=NULL,*p2=NULL;
        int iPos=0;
        if (buffer==NULL || key==NULL || value==NULL)
                return -1;

        p = buffer;
        p1 = strstr(p,key);
        if (p1==NULL)
                return -1;
        p2 = strstr(p1,":");
        if (p2==NULL)
                return -1;
        p2++;
        memset(data,0,sizeof(data));
        while (*p2!='\n' && *p2!='\0' && *p2!='\r') {
                data[iPos++]=*p2++;
        }
        if (data[iPos-1]=='\n' )
                data[iPos-1]='\0';
        if (strlen(data) == 0)
                return -1;
        strncpy(value,data,strlen(data));
        return 0;
}
char* readCfgFileX(){
        int fd=-1,length=0,readSize=0;
        char* buffer=NULL;
        struct stat fInfo;
        memset(&fInfo,0,sizeof(struct stat));

        if (stat(__CFGPATH__,&fInfo)==-1) {
                return NULL;
        }
        length = (int)fInfo.st_size;
        buffer = (char*)malloc(length+1);
        if (buffer==NULL)
                return NULL;
        fd = open(__CFGPATH__,O_RDONLY);
        if (fd==-1)
                return NULL;
        readSize = read(fd,buffer,length);
        if (readSize==-1) {
                free(buffer);
                return NULL;
        }
        close(fd);
        return buffer;
}

int dbaOpen(sqlite3 **database) {
    char dbPath[256] = {0};
    sqlite3 *pdb = NULL;
    getCfgKey(__DBPATH__,dbPath);
    if (sqlite3_open(dbPath, &pdb) != SQLITE_OK) {
        sqlite3_close(pdb);
        return -1;
    }
    *database=pdb;
    return 0;
}
void dbaClose(sqlite3 **database) {
    if (*database!=NULL) {
        sqlite3_close(*database);
    }
}
void dbaTableFree(char ***azResult){
	if (*azResult!=NULL) {
		sqlite3_free_table(*azResult);
	}
}
int dbaQuery(sqlite3 *db,char *sql,char ***azResult,int *nRow,int *nColumn,char **errmsg){
	int ret = -1;
	do{
		ret=sqlite3_get_table(db, sql, &*azResult, nRow, nColumn, &*errmsg);
		if( *errmsg!=NULL && strstr(*errmsg,"database is locked")){
			sleep(1);
			continue;
		}else{
			break;
		}
	}while(*errmsg!=NULL);
	return ret;
}
int dbaUpdate(sqlite3 *db,char *sql,char **errmsg){
	int ret = -1;
	do{
    	ret=sqlite3_exec(db, sql, NULL, NULL, &*errmsg);
    	if( *errmsg!=NULL && strstr(*errmsg,"database is locked")){
				sleep(1);
				continue;
		}else{
			break;
		}
    }while(*errmsg!=NULL);
    return ret;
}
void dbaExec(sqlite3 *db,char *sql){
	char *errmsg = NULL;
	do{
    	sqlite3_exec(db, sql, 0, 0, &errmsg);
    	if( errmsg!=NULL && strstr(errmsg,"database is locked")){
				sleep(1);
				continue;
		}else{
			break;
		}
    }while(errmsg!=NULL);
}
void do_cgi_sqlite(){
	char sql[255]={0};
    sqlite3 *db = NULL;
	char *errmsg = NULL;
	char **azResult = NULL;
	int nRow = 0;
	int nColumn = 0;
	int ret=0;
	sprintf(sql,"select szLoginID from UserInfo");
	dbaOpen(&db);
	dbaQuery(db, sql, &azResult, &nRow, &nColumn, &errmsg);
	dbaTableFree(&azResult);
	printf("nRow:%d,errmsg:%s,sql:%s\n",nRow,errmsg,sql);
	//事务使用，用于多条记录变更将提高性能
	dbaExec(db, "begin;");  //start
	sprintf(sql,"update UserInfo set szSessionID='1'");
	ret=dbaUpdate(db, sql, &errmsg);
	if (ret != SQLITE_OK) {
		dbaExec(db, "rollback;");  //rollback
		dbaClose(&db);
		return ;
	}
	sprintf(sql,"update UserInfo set szSessionID='2'");
	ret=dbaUpdate(db, sql, &errmsg);
	if (ret != SQLITE_OK) {
		dbaExec(db, "rollback;");  //rollback
		dbaClose(&db);
		return ;
	}
	sprintf(sql,"update UserInfo set szSessionID='3'");
	ret=dbaUpdate(db, sql, &errmsg);
	if (ret != SQLITE_OK) {
		dbaExec(db, "rollback;");  //rollback
		dbaClose(&db);
		return ;
	}
	dbaExec(db, "commit;");//commit
	dbaClose(&db);
}
int cgi_sqlite(){
	//do_cgi_sqlite();
	return 0;
}