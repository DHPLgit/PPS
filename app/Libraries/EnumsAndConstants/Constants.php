<?php

namespace App\Libraries\EnumsAndConstants;

class User
{

  public const UserId = "user_id";
  public const FirstName = "first_name";
  public const LastName = "last_name";
  public const MailId = "mail_id";
  public const Password = "password";
  public const Role = "role";
  public const Status = "status";
  public const RandomKey = "random_key";
  public const Otp = "otp";
}

class Access
{

  public const AccessId = "access_id";
  public const AccessName = "access_name";
  public const PageList = "page_list";
}
class OrderItems
{

  public const OrderListId = "order_list_id";
  public const OrderId = "order_id";
  public const ItemId = "item_id";
  public const CustomerId = "customer_id";
  public const ReferenceId = "reference_id";
  public const OrderDate = "order_date";
  public const Type = "type";
  public const Colour = "colour";
  public const Length = "length";
  public const Texture = "texture";
  public const ExtSize = "ext_size";
  public const Unit = "unit";
  public const BundleCount = "bundle_count";
  public const Quantity = "quantity";
  public const Status = "status";
  public const CompletionPercentage = "completion_percentage";
  public const DueDate = "due_date";
  public const Overdue = "overdue";
  public const CreatedBy = "created_by";
  public const CreatedAt = "created_at";
  public const UpdatedBy = "updated_by";
  public const UpdatedAt = "updated_at";
}
class Order
{
  public const OrderId = "order_id";
  public const CustomerId = "customer_id";
  public const OrderDate = "order_date";
  public const Status = "status";
  public const CompletionPercentage = "completion_percentage";
  public const DueDate = "due_date";
  public const Overdue = "overdue";
  public const CreatedBy = "created_by";
  public const CreatedAt = "created_at";
  public const UpdatedBy = "updated_by";
  public const UpdatedAt = "updated_at";
}
class Task
{
  public const TaskId = "task_id";
  public const IsQa = "is_qa";
  public const Part = "part";
  public const SplitFrom="split_from";
  public const IsSplit = "is_split";
  public const ParentTaskId = "parent_task_id";
  public const SiblingIdList = "sibling_id_list";
  public const OrderListId = "order_list_id";
  public const OrderItemId = "order_item_id";
  public const OrderId = "order_id";
  public const ItemId = "item_id";
  public const EmployeeId = "employee_id";
  public const SupervisorId = "supervisor_id";
  public const StartTime = "start_time";
  public const EndTime = "end_time";
  public const TimeTaken = "time_taken";
  public const TaskDetailId = "task_detail_id";
  public const NextTaskDetailId = "next_task_detail_id";
  public const Sizing = "sizing";
  public const OutLength = "out_length";
  public const OutColour = "out_colour";
  public const OutTexture = "out_texture";
  public const OutQty = "out_qty";
  public const OutExtSize = "out_ext_size";
  public const OutType = "out_type";
  public const Unit = "unit";
  public const Status = "status";
  public const MergeStatus="merge_status";
  public const CreatedBy = "created_by";
  public const CreatedAt = "created_at";
  public const UpdatedBy = "updated_by";
  public const UpdatedAt = "updated_at";
}
class Stock
{
  public const StockListId = "stock_list_id";
  public const StockId = "stock_id";
  public const ParentId = "parent_id";
  public const ActiveStatus = "active_status";
  public const Colour = "colour";
  public const Length = "length";
  public const Texture = "texture";
  public const Unit = "unit";
  public const Quantity = "quantity";
  public const Status = "status";
  public const DeleteStatus = "delete_status";
  public const Type = "type";
  public const ExtSize = "ext_size";
  public const Date="date";
  public const CreatedBy = "created_by";
  public const CreatedAt = "created_at";
  public const UpdatedBy = "updated_by";
  public const UpdatedAt = "updated_at";
}

class TaskDetail
{
  public const TaskDetailId = "task_detail_id";
  public const TaskName = "task_name";
  public const OrderId = "order_id";
  public const TimeTaken = "time_taken";
  public const Supervisor = "supervisor";
  public const DepartmentId = "dept_id";
  public const IsQa = "is_qa";
  public const DaysTaken = "days_taken";
  public const CreatedBy = "created_by";
  public const QualityAnalyst = "quality_analyst";
  public const ParentTask = "parent_task";
  public const CreatedAt = "created_at";
  public const UpdatedBy = "updated_by";
  public const UpdatedAt = "updated_at";
}

class Employee
{

  public const Id = "id";
  public const Name = "name";
  public const EmpCode = "emp_code";
  public const PhoneNo = "phone_no";
  public const DOJ = "doj";
  public const DOB = "dob";
  public const Designation = "designation";
  public const Address = "address";
  public const Status = "status";
}

class DeptEmpMap
{

  public const DeptEmpMapId = "dept_emp_map_id";
  public const DeptId = "dept_id";
  public const SupervisorId = "supervisor_id";
  public const EmployeeIds = "employee_ids";
  public const Status="status";
}

class QualityCheck
{

  public const QCId = "qc_id";
  public const QCName = "qc_name";
  public const Description = "description";
}

class TaskInput
{
  public const InputId = "input_id";
  public const TaskId = "task_id";
  public const TimeTaken = "time_taken";
  public const InputCount   = "input_count	";
  public const InLength = "in_length";
  public const InColour = "in_colour";
  public const InQuantity = "in_quantity";
  public const InTexture = "in_texture";
  public const InExtSize = "in_ext_size";
  public const InType = "in_type";

  public const CreatedBy = "created_by";
  public const QualityAnalyst = "quality_analyst";
  public const ParentTask = "parent_task";
  public const CreatedAt = "created_at";
  public const UpdatedBy = "updated_by";
  public const UpdatedAt = "updated_at";
}

class OrderStockMap
{
  public const OrderListId = "order_list_id";
  public const StockListId = "stock_list_id";
}

class WtStd
{

  public const WtStdId = "weight_std_id";
  public const Length = "length";
  public const Weight = "weight";
}

class Department
{

  public const DepartmentId = "dept_id";
  public const DepartmentName = "dept_name";
  public const CompletionPercentage = "completion_percentage";
}
class Customer
{

  public const CustomerId = "customer_id";
  public const CustomerName = "customer_name";
  public const Status = "status";
  public const UpdatedBy = "updated_by";
  public const CreatedBy = "created_by";
}
