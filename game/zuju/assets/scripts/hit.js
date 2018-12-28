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
        is_collision:false,
    },
    onLoad() {
        cc.director.getCollisionManager().enabled = true;
        //cc.director.getCollisionManager().enabledDebugDraw = true;
        //cc.director.getCollisionManager().enabledDrawBoundingBox = true;
        this.pos_collision = this.node.getPosition();
    },
    
    touchMove(event) {

        //cc.log("移动跟随移动", this.node.name, this.pos_collision);
        var delta = event.touch.getDelta();
        this.node.x += delta.x;
        this.node.y += delta.y;
        
    },
    touchCancel(event){
         cc.log("当手指在目标节点区域外离开屏幕时", this.name, this.pos_collision);
         this.node.setPosition(this.pos_collision);
         //this.setPosition(cc.zc.pos_collision);
    },
    touchEnd(event) {
        cc.log("当手指在目标节点区域内离开屏幕时", this.node.name, this.pos_collision);
        this.node.setPosition(this.pos_collision);
    },
    touchStart(event){
        cc.log("当手指触点落在目标节点区域内时", this.name, this.pos_collision);
    },
    registerEvent() {
       //用于手指移动目标的跟随
       this.node.on(cc.Node.EventType.TOUCH_MOVE, this.touchMove, this);
       //当手指触点落在目标节点区域内时
       this.node.on(cc.Node.EventType.TOUCH_START, this.touchStart, this);
       //当手指在目标节点区域内离开屏幕时
       this.node.on(cc.Node.EventType.TOUCH_END, this.touchEnd, this);
       //当手指在目标节点区域外离开屏幕时
       this.node.on(cc.Node.EventType.TOUCH_CANCEL, this.touchCancel, this);
    },
    unRegisterEvent() {
        this.node.off(cc.Node.EventType.TOUCH_MOVE, this.touchMove, this);
        this.node.off(cc.Node.EventType.TOUCH_START, this.touchStart, this);
        this.node.off(cc.Node.EventType.TOUCH_END, this.touchEnd, this);
        this.node.off(cc.Node.EventType.TOUCH_CANCEL, this.touchCancel, this);
    },
    start() {
        
        this.registerEvent();
    },
    onCollisionEnter: function (other, self) {
        //cc.log(other.node.getComponent('cloud_down_ extra').is_collision);
        var cloud_down = other.node.getComponent('cloud_down_extra');
        var cloud_up   = this;
        //两个云朵碰撞了,就不能再移动位置
        if(cloud_down.is_collision == false && this.is_collision == false){
           
            //设置碰撞坐标
            //var pos = other.node.getPosition();
            cloud_up.pos_collision = other.node.getPosition();
            self.node.setPosition(cloud_up.pos_collision);  
            cloud_down.segment = cloud_up.node.getChildByName("label").getComponent(cc.Label).string;
            cloud_down.is_collision = true; //下面的云已碰撞设置
            cloud_up.is_collision = true;       //上面的云已碰撞设置

            //把碰撞顺序记录下来
            //cc.zc.INFO.answer.push(cloud_up.node.name);
            //cc.zc.INFO.answer = cc.zc.INFO.answer+this.node.getChildByName("label").getComponent(cc.Label).string;
            cc.zc.audio_mgr.playSFX("jump.mp3");   //播放云朵碰撞的声音
            cc.log("符合条件设置坐标",cc.zc.INFO.answer,this.is_collision,cloud_down.is_collision);
            
            
        }  
        
        /* 
        //如果没碰撞过,设置坐标
        if (this.is_collision == false){
            //设置碰撞坐标
            //var pos = other.node.getPosition();
            this.pos_collision = other.node.getPosition();
            self.node.setPosition(this.pos_collision);
            this.is_collision = true;
            cc.log("没碰撞过,设置坐标");
            //this.unRegisterEvent();
        }
        */
        //cc.zc.pos_collision = self.node.getPosition();
        //cc.log("碰撞了->>(other,self)", this.pos_collision, other.node.name, self.node.name, other.node.getPosition(), self.node.getPosition(), other);
        //cc.log("碰撞了->>", this.pos_collision);
    },
    onCollisionStay: function (other, self) {
        
        //cc.log('碰撞持续中-->>', this.pos_collision);
    },
    onCollisionExit: function (other, self) {
        //cc.log('碰撞结束-->>', this.pos_collision);
            // this.touchingNumber--;

            // cc.log("碰撞离开onCollisionExit-->>",this.touchingNumber,this.pos);
            // if (this.touchingNumber === 0) {
            //     this.node.color = cc.Color.BLUE;
            // }
    },
    // LIFE-CYCLE CALLBACKS:




    // update (dt) {},
});
