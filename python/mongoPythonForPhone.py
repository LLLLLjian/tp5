# 随机数
import random
# json相关
import json
# 第三方手机相关信息
from phone import Phone
# mongo相关模块
from pymongo import MongoClient
from pymongo import ReturnDocument
# 时间模块
import time
# 导入mysql相关模块
import pymysql
import sys

# 随机生成手机号码
def createPhone():
	prelist=["130","131","132","133","134","135","136","137","138","139","147","150","151","152","153","155","156","157","158","159","186","187","188"]
	return random.choice(prelist)+"".join(random.choice("0123456789") for i in range(8))

def insertPhone(mobile):
	global dbForMongo, cursor, dbForMysql

	collectionForPhone = dbForMongo.phone_log
	resultForRow = collectionForPhone.find_one({'mobile': mobile})
	if resultForRow:
		return
            
	result = p.find(mobile)
	result1 = json.dumps(result)
	result2 = json.loads(result1)
            
	now = int(time.time())
	timeStruct = time.localtime(now)
	strTime = time.strftime("%Y-%m-%d %H:%M:%S", timeStruct)
	strDate = time.strftime("%Y-%m-%d", timeStruct)
	data = {"strTime" : strTime, "strDate" : strDate, "now" : now }
	data['area_code'] = result2['area_code']
	data['mobile'] = result2['phone']
	data['city'] = result2['city']
	data['phone_type'] = result2['phone_type']
	data['province'] = result2['province']
	data['zip_code'] = result2['zip_code']

	result = dbForMongo.counters.find_one_and_update({'_id': 'phone_log'},{'$inc': {'seq': 1}},projection={'seq': True,'_id': False},upsert=True,return_document=ReturnDocument.AFTER)
	data["_id"] = result["seq"]

	collectionForPhone.insert_one(data)
	
	sql = "INSERT INTO phone_log(mobile, phone_type, province, city, zip_code, area_code, create_time) VALUES (%s,%s,%s,%s,%s,%s,%s)" % (data['mobile'], repr(data['phone_type']), repr(data['province']), repr(data['city']), data['zip_code'], data['area_code'], data['now'])
	cursor.execute(sql)
	dbForMysql.commit()

client = MongoClient()
dbForMongo = client.llllljian
# 打开数据库连接
dbForMysql = pymysql.connect("127.0.0.1", "root", "liujian", "tentcent_tp", charset='utf8')
# 使用cursor方法获取操作游标
cursor = dbForMysql.cursor()
p = Phone()

if __name__ == '__main__':
	if len(sys.argv) < 2 :
		mobile = createPhone()
	else:
		mobile = sys.argv[1]
	#print(mobile)
	insertPhone(mobile)
