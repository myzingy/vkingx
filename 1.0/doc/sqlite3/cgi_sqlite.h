#ifndef __cgi_sqlite_h__
#define  __cgi_sqlite_h__

extern int cgi_sqlite();
extern int getCfgKey(char* key,char* value);
extern char* readCfgFileX();
extern int dbaOpen(sqlite3 **database);
extern void dbaClose(sqlite3 **database);
extern void dbaTableFree(char ***azResult);
extern int dbaQuery(sqlite3 *db,char *sql,char ***azResult,int *nRow,int *nColumn,char **errmsg);
extern int dbaUpdate(sqlite3 *db,char *sql,char **errmsg);
extern void dbaExec(sqlite3 *db,char *sql);
#define __CFGPATH__ "/etc/v2d2config.conf/v2d2.config"
#define __DBPATH__ "DB_Path"

#endif /* __cgi_sqlite_h__ */

