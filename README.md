# PRGC
***
#### 使用方法：
1.先将所有文件下载到网页目录
2.使用以下指令分配权限
```bash
chmod -R 755 ./
chmod -R +x ./
```
3.打开daemon守护进程
```bash
./daemon.sh
```
***
#### 注意：
1.每次重启需要重新启动守护进程
```bash
./daemon.sh
```
2.用户数据保存在 config/users.php，可配置多个用户，请按照PHP数组方式填写
