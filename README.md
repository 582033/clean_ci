####第三方库
CodeIgniter 2.1.3
Smarty 3.1.13
jquery 1.9.0
jquery.json-2.4.min.js
colorbox 1.3.29

####CI框架修改说明
* 增加library作为contrller跟model的中间层
* model library相互与自身间可相互调用(config/my_load.conf来实现,必须的base类可继续使用autoload.conf来加载)
* view层增加smarty支持
* 默认使用redis缓存,如不需要可删除`config/autoload.conf`下$autoload['libraris']的`redis`
* 可把所有_与发布环境不同的本地配置变量_放到`config/local.config.conf`以覆盖_发布环境配置文件_的变量(此文件不要加入版本控制)

