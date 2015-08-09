### Big Picture

1. Support console actions as well as web actions.
2. Be able to add and configure outside bundles of functionality.
3. Use React style "components" for generating html.
4. Extensive automated testing of stable code

### Dependency Injection Container

1. Do as much wiring/configuring ss possible using the container.  
2. Avoid the new operator.
3. Inject and use factory services when applicable.
4. Avoid the need for direct access to the container.
5. Be able to plugin different container implementations.
6. On demand service registration based on current action.

### PSR7 Compliant Request/Response objects

1. Only use PSR7 methods.  
2. Should be possible to plug in different PSR7 request/response implementations.

### Middleware 

1. No large controllers.
2. Action oriented.  Each action is a component.
3. Each action maps to a callable middleware action which accepts Request/Response objects.
4. Middleware actions can wrapped with additional middleware callables.
