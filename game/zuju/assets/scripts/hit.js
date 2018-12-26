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
        cc.director.getCollisionManager().enabledDebugDraw = true;
        cc.director.getCollisionManager().enabledDrawBoundingBox = true;
        this.pos_collision = this.node.getPosition();
    },
    touchUp(event) {
        cc.log("当鼠标从按下状态松开", this.node.name, this.pos_collision);
        this.node.setPosition(this.pos_collision);
    },
    touchMove(event) {
        var delta = event.touch.getDelta();
        this.node.x += delta.x;
        this.node.y += delta.y;
        cc.log("移动跟随", this.node.name, this.pos_collision);
    },
    touchCancel(event){
         cc.log("当手指在目标节点区域外离开屏幕时", this.name, this.pos_collision);
         this.node.setPosition(this.pos_collision);
         //this.setPosition(cc.zc.pos_collision);
    },
    registerEvent() {

        // this.node.on(cc.Node.EventType.MOUSE_DOWN, function (event) {

        //     event.stopPropagation();
        // }, this);
        // //鼠标按下
        // this.node.on(cc.Node.EventType.MOUSE_DOWN, function (event) {

        //     this.pos_collision = this.node.getPosition();
        //     cc.log("鼠标按下", this.node.name, this.pos_collision);
        // }, this);
       //用于手指移动目标的跟随
       this.node.on(cc.Node.EventType.TOUCH_MOVE, this.touchMove, this);
       //当鼠标从按下状态松开
       this.node.on(cc.Node.EventType.MOUSE_UP, this.touchUp, this);
       //当手指在目标节点区域外离开屏幕时
       this.node.on(cc.Node.EventType.TOUCH_CANCEL, this.touchCancel, this);
    },
    unRegisterEvent() {
        this.node.off(cc.Node.EventType.MOUSE_UP, this.touchUp, this);
        this.node.off(cc.Node.EventType.TOUCH_MOVE, this.touchMove, this);
        this.node.off(cc.Node.EventType.TOUCH_CANCEL, this.touchCancel, this);
    },
    start() {
        
        this.registerEvent();
    },
    onCollisionEnter: function (other, self) {
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
        
        //cc.zc.pos_collision = self.node.getPosition();
        cc.log("碰撞了->>(other,self)", this.pos_collision, other.node.name, self.node.name, other.node.getPosition(), self.node.getPosition(), other);
        cc.log("碰撞了->>", this.pos_collision);
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
