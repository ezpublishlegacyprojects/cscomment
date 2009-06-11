<?php /*

[CSComment]
MinUsername=1
MaxUsername=50
MinMessageLenght=3
MaxMessageLenght=500
shardingActive=enabled
#If enabled cache is cleared through cronjob, otherwise instantly on comment publishing.
delayCacheClear=disabled


#After how many records create new sharding table
[ShardingData]
ShardingStep=500000


*/ ?>