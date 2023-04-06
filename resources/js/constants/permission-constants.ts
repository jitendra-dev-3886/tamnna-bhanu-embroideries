const PERMISSION_CONSTANTS = {

  // Start -> second level permissions
  USERS: { module: 'users', subModule: '' },
  ROLES: { module: 'roles', subModule: '' },

  CATEGORIES: { module: 'categories', subModule: '' }, 
  PRODUCTS: { module: 'products', subModule: '' }, 
  ORDERS: { module: 'orders', subModule: '' }, 
  CARTS: { module: 'carts', subModule: '' }, 

  PERMISSIONS: { module: 'permissions', subModule: '' },
  ROLEPERMISSIONS: { module: 'permission-role-mappings', subModule: '' },
  // End -> second level permissions

  // Start -> Permission Dialog
  PERMISSION_DIALOG_TITLE: 'Permission Error',
  PERMISSION_DIALOG_MSG: "You don't have permission to access this module, please contact your Administrator!",
  PERMISSION_DIALOG_FUNCTIONALITY_MSG: "You don't have permission to access this functionality, please contact your Administrator!",
  // End - Permission Dialog

  CAN_ACCESS: 'can-access',
  CAN_EDIT: 'can-edit',
  CAN_DELETE: 'can-delete',
};
export default PERMISSION_CONSTANTS;
