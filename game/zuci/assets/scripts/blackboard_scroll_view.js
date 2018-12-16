// Learn cc.Class:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/class.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/class.html
// Learn Attribute:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/reference/attributes.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/reference/attributes.html
// Learn life-cycle callbacks:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/life-cycle-callbacks.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/life-cycle-callbacks.html

cc.Class({
    extends: cc.Component,

    properties: {
        // foo: {
        //     // ATTRIBUTES:
        //     default: null,        // The default value will be used only when the component attaching
        //                           // to a node for the first time
        //     type: cc.SpriteFrame, // optional, default is typeof default
        //     serializable: true,   // optional, default is true
        // },
        // bar: {
        //     get () {
        //         return this._bar;
        //     },
        //     set (value) {
        //         this._bar = value;
        //     }
        // },
        scrollview: {
                type: cc.ScrollView,
                default: null,
            },
        word_on_blackboard_prefab: {
            type: cc.Prefab,
            default: null,
        },
    },

    // LIFE-CYCLE CALLBACKS:

    // onLoad () {},
    
    start () {
        //黑板上显示的字的处理
        for(var i = 0;i < cc.zc.INFO[cc.zc.lesson].target_word.length;i++){

            var opt_item = cc.instantiate(this.word_on_blackboard_prefab);
            //stringObject.substr(start,length) start 要抽取的子串的起始下标,length子串中的字符数。必须是数值
            if(cc.zc.INFO[cc.zc.lesson].target_word.substr(i,1) == cc.zc.INFO[cc.zc.lesson].new_word){
                opt_item.getChildByName("img_game_ask").active = true;   
            }else{
                opt_item.getChildByName("label").getComponent(cc.Label).string = cc.zc.INFO[cc.zc.lesson].target_word.substr(i,1);
            }
            this.scrollview.content.addChild(opt_item);
        }
        /*
         //处理分词
         var word = cc.zc.INFO[cc.zc.lesson].word.split(",");
         for (var i = 0; i < word.length; i++) {
             var opt_item = cc.instantiate(this.word_on_blackboard_prefab);
             //var block_ask = opt_item.getChildByName("img_word_block_ask");
             
             //console.log();
             //this.word_on_desk_prefab.label.string = "A";
             
             if (cc.zc.INFO[cc.zc.lesson].miss-1 == i){
                opt_item.getChildByName("img_game_ask").active = true;
                //console.log("缺少字的地方", opt_item.getChildByName("img_game_ask").getComponent(cc.Sprite));
             }else{
                opt_item.getChildByName("label").getComponent(cc.Label).string = word[i];
                //block_ask.getChildByName("label").getComponent(cc.Label).string = word[i];
             }
             
             
            //  console.log(opt_item.getChildByName("label").getComponent(cc.Label));
            this.scrollview.content.addChild(opt_item);

         }
         */
    },

    update (dt) {
        
    },
});
