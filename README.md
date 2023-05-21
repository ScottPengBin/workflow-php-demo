

## 设计思路

通过继承BaseWorkFlowService类，实现类的动态代理。

- 划定Task执行的粒度；本次使用的是Service+method为一个Task粒度，也可根据路由【Controller+method】为一个粒度。
- 执行Task时，去检查是否为需要触发的方法。

### 优势
- 相较于传统埋点方式，代码侵入少。
- 继承了BaseWorkFlowService的类，都会被接管，覆盖面广，新增一个Method，则就被加入代理，不需要额外配置。
- 在执行相应逻辑时，可以自己根据实际情况，在App\Workflow【可配置】文件夹下写对应类的触发条件等，默认则执行公共BaseEngineWorkflow中的方法。可扩展性强
